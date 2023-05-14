<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI;
class ArticleGeneratorController extends Controller
{
    public function index(Request $request)
{
    if ($request->title == null) {
        return;
    }

    $title = $request->title;

    $client = OpenAI::client(env('OPENAI_API_KEY'));
    $response = $client->images()->create([
        'prompt' => 'create good logo for IT Company, company name is Cloud1 Web Solutions',
        'n' => 1,
        'size' => '256x256',
        'response_format' => 'url',
    ]);
    
    $response->created; // 1589478378
    
    foreach ($response->data as $data) {
        $data->url; // 'https://oaidalleapiprodscus.blob.core.windows.net/private/...'
        $data->b64_json; // null
    }
    echo "<pre>";
    print_r($response); echo "</pre>"; die;
    
    $response->toArray();
    $response = $client->chat()->create([
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'user', 'content' => 'my name is gaurav, i need to know about website design'],
        ],
    ]);
    
    $response->id; // 'chatcmpl-6pMyfj1HF4QXnfvjtfzvufZSQq6Eq'
    $response->object; // 'chat.completion'
    $response->created; // 1677701073
    $response->model; // 'gpt-3.5-turbo-0301'
    
    foreach ($response->choices as $result) {
        $result->index; // 0
        $result->message->role; // 'assistant'
        $result->message->content; // '\n\nHello there! How can I assist you today?'
        $result->finishReason; // 'stop'
    }    
    $response->toArray(); // ['id' => 'chatcmpl-6pMyfj1HF4QXnfvjtfzvufZSQq6Eq', ...]
   
    $result = $client->completions()->create([
        "model" => "text-davinci-003",
        "temperature" => 0.7,
        "top_p" => 1,
        "frequency_penalty" => 0,
        "presence_penalty" => 0,
        'max_tokens' => 600,
        'prompt' => sprintf( $title),
    ]);

    $content = trim($result['choices'][0]['text']);


    return view('write', compact('title', 'content'));
}
}
