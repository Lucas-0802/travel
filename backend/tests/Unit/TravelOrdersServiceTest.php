<?php

namespace Tests\Unit;

use App\Models\TravelOrder;
use App\Services\TravelOrders\TravelOrdersService;
use App\Repositories\TravelOrders\TravelOrdersRepositoryInterface;
use App\Services\Notification\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;
use Exception;

class TravelOrdersServiceTest extends TestCase
{
    protected $travelOrdersRepository;
    protected $notificationService;
    protected $travelOrdersService;

    public function setUp(): void
    {
        parent::setUp();

        // Mockando as dependências
        $this->travelOrdersRepository = Mockery::mock(TravelOrdersRepositoryInterface::class);
        $this->notificationService = Mockery::mock(NotificationService::class);

        // Criando a instância do TravelOrdersService com as dependências mockadas
        $this->travelOrdersService = new TravelOrdersService(
            $this->travelOrdersRepository,
            $this->notificationService
        );
    }

    public function testCreateTravelOrderSuccess()
    {
        $data = ['destination' => 'Paris', 'departure_date' => '2025-05-01'];
        $mockedOrder = new TravelOrder(['id' => 1, 'destination' => 'Paris', 'departure_date' => '2025-05-01']);

        // Mockando a chamada do repositório
        $this->travelOrdersRepository->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($arg) {
                Log::info('Mock recebendo:', $arg);
                return true;
            }))
            ->andReturn($mockedOrder);

        Auth::shouldReceive('id')->once()->andReturn(1);

        $response = $this->travelOrdersService->create($data);

        $this->assertEquals('travel_order_created_successfully', $response['message']);
        $this->assertEquals(201, $response['status']);
        $this->assertEquals($mockedOrder, $response['order']);
    }

    public function testUpdateTravelOrderSuccess()
    {
        $data = ['destination' => 'London', 'departure_date' => '2025-06-01'];
        $mockedOrder = new TravelOrder(['id' => 2, 'destination' => 'London', 'departure_date' => '2025-06-01']);

        $this->travelOrdersRepository->shouldReceive('update')
            ->once()
            ->with(2, $data)
            ->andReturn($mockedOrder);

        $response = $this->travelOrdersService->update($data, '2');

        $this->assertEquals('travel_order_updated_successfully', $response['message']);
        $this->assertEquals(200, $response['status']);
        $this->assertEquals($mockedOrder, $response['order']);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
