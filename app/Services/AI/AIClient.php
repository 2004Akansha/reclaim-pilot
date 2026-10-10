<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class AIClient
{
    protected string $baseUrl;
    protected string $model;
    protected string $visionModel;
    protected int $timeout;

    public function __construct()
    {
        $this->baseUrl = rtrim(
            config('services.ollama.base_url'),
            '/'
        );

        $this->model = config(
            'services.ollama.model'
        );

        $this->visionModel = config(
            'services.ollama.vision_model'
        );

        $this->timeout = (int) config(
            'services.ollama.timeout',
            120
        );
    }

    /**
     * Send a text-only request to Ollama.
     */
    public function generate(
        string $systemPrompt,
        string $userPrompt,
        bool $json = false
    ): string {

        Log::info('Sending request to Ollama.', [
            'model' => $this->model,
            'json' => $json,
        ]);

        $payload = [
            'model' => $this->model,

            'stream' => false,

            'messages' => [
                [
                    'role' => 'system',
                    'content' => $systemPrompt,
                ],
                [
                    'role' => 'user',
                    'content' => $userPrompt,
                ],
            ],
        ];

        if ($json) {
            $payload['format'] = 'json';
        }

        try {

            $response = Http::timeout($this->timeout)
                ->post(
                    $this->baseUrl . '/api/chat',
                    $payload
                );

        } catch (\Throwable $e) {

            Log::error('Ollama connection failed.', [
                'model' => $this->model,
                'error' => $e->getMessage(),
            ]);

            throw new RuntimeException(
                'Could not connect to Ollama.',
                0,
                $e
            );
        }

        if ($response->failed()) {

            Log::error('Ollama returned an HTTP error.', [
                'model' => $this->model,
                'status' => $response->status(),
            ]);

            throw new RuntimeException(
                'Ollama request failed with HTTP status ' .
                $response->status()
            );
        }

        $content = $response->json('message.content');

        if (
            !is_string($content) ||
            trim($content) === ''
        ) {

            Log::error('Ollama returned an empty response.', [
                'model' => $this->model,
            ]);

            throw new RuntimeException(
                'Ollama returned an empty response.'
            );
        }

        Log::info('Ollama response received.', [
            'model' => $this->model,
        ]);

        return trim($content);
    }


    /**
     * Send a text + image request to Ollama.
     */
    public function generateWithImages(
        string $systemPrompt,
        string $userPrompt,
        array $images
    ): string {

        Log::info('Sending vision request to Ollama.', [
            'model' => $this->visionModel,
            'image_count' => count($images),
        ]);

        try {

            $response = Http::timeout($this->timeout)
                ->post(
                    $this->baseUrl . '/api/chat',
                    [
                        'model' => $this->visionModel,

                        'stream' => false,

                        'format' => 'json',

                        'messages' => [
                            [
                                'role' => 'system',
                                'content' => $systemPrompt,
                            ],
                            [
                                'role' => 'user',
                                'content' => $userPrompt,
                                'images' => $images,
                            ],
                        ],
                    ]
                );

        } catch (\Throwable $e) {

            Log::error('Ollama vision connection failed.', [
                'model' => $this->visionModel,
                'error' => $e->getMessage(),
            ]);

            throw new RuntimeException(
                'Could not connect to Ollama vision model.',
                0,
                $e
            );
        }

        if ($response->failed()) {

            Log::error('Ollama vision request failed.', [
                'model' => $this->visionModel,
                'status' => $response->status(),
            ]);

            throw new RuntimeException(
                'Ollama vision request failed with HTTP status ' .
                $response->status()
            );
        }

        $content = $response->json('message.content');

        if (
            !is_string($content) ||
            trim($content) === ''
        ) {

            Log::error(
                'Ollama returned an empty vision response.',
                [
                    'model' => $this->visionModel,
                ]
            );

            throw new RuntimeException(
                'Ollama returned an empty vision response.'
            );
        }

        Log::info('Ollama vision response received.', [
            'model' => $this->visionModel,
        ]);

        return trim($content);
    }


    /**
     * Get configured text model.
     */
    public function getModel(): string
    {
        return $this->model;
    }


    /**
     * Get configured vision model.
     */
    public function getVisionModel(): string
    {
        return $this->visionModel;
    }
}