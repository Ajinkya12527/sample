<?php

// app/Http/Controllers/ChatController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http; // For HTTP requests

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $message = $request->input('message');
         $apiKey = env('OPENAI_API_KEY'); 

        // The data to send with the request (prompt and settings)
        $data = [
            'model' => 'gpt-3.5-turbo',
            'prompt' => 'Say hello to the world!',
            'max_tokens' => 50,
        ];

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $apiKey, // Include Bearer token
            ])
            ->post('https://api.openai.com/v1/completions', $data);

            if ($response->successful()) {
                $responseBody = $response->json(); 
                $generatedText = $responseBody['choices'][0]['text']; 
                return response()->json(['reply' => $generatedText]);
            } else {
                return response()->json(['error' => 'Error from OpenAI API: ' . $response->body()]);
            }
        } catch (\Exception $e) {
            // Handle exception
            return response()->json(['error' => 'Request failed: ' . $e->getMessage()]);
        }
    }
}
