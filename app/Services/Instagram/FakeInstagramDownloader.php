<?php

namespace App\Services\Instagram;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\App;
use Mockery;
use Mockery\MockInterface;

class FakeInstagramDownloader
{
    public function __construct()
    {
        $html = file_get_contents(base_path('tests/Instagram/fixtures/valid.html'));

        $client = $this->createClient($html);

        App::instance(InstagramDownloader::class, new InstagramDownloader($client));
    }

    public function privateProfile()
    {
        $html = file_get_contents(base_path('tests/Instagram/fixtures/private.html'));

        $client = $this->createClient($html);

        App::instance(InstagramDownloader::class, new InstagramDownloader($client));
    }

    public function nonExistentProfile()
    {
        $client = $this->createClient('', 404);

        App::instance(InstagramDownloader::class, new InstagramDownloader($client));
    }

    public function avatar(string $avatar)
    {
        App::instance(
        	InstagramDownloader::class,
        	Mockery::mock(InstagramDownloader::class, function (MockInterface $mock) use ($avatar) {
        	    $mock->shouldReceive('getAvatar')->andReturn($avatar);
        	})
   		);
    }

    protected function createClient(string $html, int $code = 200)
    {
        $headers = [
            'Set-Cookie' => 'cookie',
        ];

        $response = new Response($code, $headers, $html);
        $mock = new MockHandler([$response]);
        $handler = HandlerStack::create($mock);

        return new Client(['handler' => $handler]);
    }
}
