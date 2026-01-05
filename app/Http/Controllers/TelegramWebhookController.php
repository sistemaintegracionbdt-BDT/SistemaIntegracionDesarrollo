<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\adts;
use App\coordinadores;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class TelegramWebhookController extends Controller
{
    protected $token;

    public function __construct() {
        $this->token = config('services.telegram.token');
    }

    public function handleWebhook(Request $request)
    {
        $update = $request->all();
        Log::info("Webhook update recibido: " . json_encode($update));

        if (!isset($update['callback_query'])) {
            return response()->json(['status' => 'ignored']);
        }

        $callback = $update['callback_query'];
        $callbackId = $callback['id'];
        $chatId = $callback['from']['id'];
        $messageId = $callback['message']['message_id'] ?? null;

        if (!isset($callback['data'])) {
            $this->sendText($chatId, "Callback inválido.");
            return response()->json(['status' => 'invalid']);
        }

        $data = explode("_", $callback['data']);
        if (count($data) < 2) {
            $this->sendText($chatId, "Formato de callback incorrecto.");
            return response()->json(['status' => 'invalid']);
        }

        $opcion = $data[0];
        $adt_id = $data[1];

        $adt = adts::find($adt_id);
        if (!$adt) {
            $this->sendText($chatId, "El ADT ya no existe o no se encontró.");
            return response()->json(['status' => 'not_found']);
        }

        switch ($opcion) {
            case 'VALIDAR APERTURA ADT':
                $responseText = "<i>{$adt->NOMBRE}</i>\n\n<b>APERTURA VALIDADA</b>";
                $this->actualizarEstatusAdt($adt, 'ABIERTA');
                break;
            case 'VALIDAR CIERRE ADT':
                $responseText = "<i>{$adt->NOMBRE}</i>\n\n<b>CIERRE VALIDADO</b>";
                $this->actualizarEstatusAdt($adt, 'CERRADA');
                break;
            case 'RECHAZAR APERTURA ADT':
                $responseText = "<i>{$adt->NOMBRE}</i>\n\n<b>APERTURA RECHAZADA</b>";
                break;
            case 'RECHAZAR CIERRE ADT':
                $responseText = "<i>{$adt->NOMBRE}</i>\n\n<b>CIERRE RECHAZADO</b>";
                break;
            default:
                $responseText = "Opción no reconocida.";
                break;
        }

        $this->sendText($chatId, $responseText);
        $this->answerCallback($callbackId, "Tu selección fue procesada.");

        if ($messageId) {
            $this->deleteMessage($chatId, $messageId);
        }

        $coordinadores = coordinadores::all();
        foreach($coordinadores as $coordinador) {
            $this->sendText($coordinador->TELEGRAM, "¡AVISO!\n\n" . $responseText);
        }

        return response()->json(['status' => 'success']);
    }

    private function sendText($chat_id, $text)
    {
        $url = "https://api.telegram.org/bot{$this->token}/sendMessage";
        $client = new Client();

        try {
            $res = $client->post($url, [
                'form_params' => [
                    'chat_id' => $chat_id,
                    'text' => $text,
                    'parse_mode' => 'HTML',
                    'disable_web_page_preview' => true,
                ]
            ]);
            Log::info("Telegram sendText response: " . $res->getBody());
        } catch (\Exception $e) {
            Log::error("Error Telegram sendText: " . $e->getMessage());
        }
    }

    private function answerCallback($callbackId, $text)
    {
        $url = "https://api.telegram.org/bot{$this->token}/answerCallbackQuery";
        $client = new Client();

        try {
            $res = $client->post($url, [
                'form_params' => [
                    'callback_query_id' => $callbackId,
                    'text' => $text,
                    'show_alert' => false,
                ]
            ]);
            Log::info("Telegram answerCallback response: " . $res->getBody());
        } catch (\Exception $e) {
            Log::error("Error Telegram answerCallback: " . $e->getMessage());
        }
    }

    private function deleteMessage($chatId, $messageId)
    {
        $url = "https://api.telegram.org/bot{$this->token}/deleteMessage";
        $client = new Client();

        try {
            $res = $client->post($url, [
                'form_params' => [
                    'chat_id' => $chatId,
                    'message_id' => $messageId,
                ]
            ]);
            Log::info("Telegram deleteMessage response: " . $res->getBody());
        } catch (\Exception $e) {
            Log::error("Error Telegram deleteMessage: " . $e->getMessage());
        }
    }

    private function actualizarEstatusAdt(adts $adt, $estatus)
    {
        $adt->ESTATUS_ACTUAL = $estatus;
        $adt->save();
        Log::info("ADT {$adt->id} actualizado a estatus {$estatus}");
    }
}
