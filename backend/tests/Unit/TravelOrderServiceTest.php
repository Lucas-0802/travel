<?php

namespace Tests\Unit;

use App\Services\TravelOrders\TravelOrdersService;
use App\Repositories\TravelOrders\TravelOrdersRepositoryInterface;
use App\Services\Notification\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Mockery;
use PHPUnit\Framework\TestCase;
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
        $mockedOrder = (object) ['id' => 1, 'destination' => 'Paris', 'departure_date' => '2025-05-01'];

        // Mockando a chamada do repositório
        $this->travelOrdersRepository->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn($mockedOrder);

        // Simulando o Auth::id()
        Auth::shouldReceive('id')->once()->andReturn(1);

        // Chamando o método
        $response = $this->travelOrdersService->create($data);

        // Assertivas
        $this->assertEquals('travel_order_created_successfully', $response['message']);
        $this->assertEquals(201, $response['status']);
        $this->assertEquals($mockedOrder, $response['order']);
    }

    public function testCreateTravelOrderFailure()
    {
        $data = ['destination' => 'Paris', 'departure_date' => '2025-05-01'];

        // Simulando uma exceção no repositório
        $this->travelOrdersRepository->shouldReceive('create')
            ->once()
            ->with($data)
            ->andThrow(new Exception('Database error'));

        // Simulando o Auth::id()
        Auth::shouldReceive('id')->once()->andReturn(1);

        // Esperando que a exceção seja lançada
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('unable_to_create_travel_order');

        // Chamando o método
        $this->travelOrdersService->create($data);
    }

    public function testUpdateTravelOrderSuccess()
    {
        $data = ['destination' => 'London', 'departure_date' => '2025-06-01'];
        $mockedOrder = (object) ['id' => 2, 'destination' => 'London', 'departure_date' => '2025-06-01'];

        // Mockando a chamada do repositório
        $this->travelOrdersRepository->shouldReceive('update')
            ->once()
            ->with(2, $data)
            ->andReturn($mockedOrder);

        // Chamando o método
        $response = $this->travelOrdersService->update($data, '2');

        // Assertivas
        $this->assertEquals('travel_order_updated_successfully', $response['message']);
        $this->assertEquals(200, $response['status']);
        $this->assertEquals($mockedOrder, $response['order']);
    }

    public function testDeleteTravelOrderSuccess()
    {
        $request = ['id' => 3];

        // Mockando a chamada do repositório
        $this->travelOrdersRepository->shouldReceive('delete')
            ->once()
            ->with(3);

        // Chamando o método
        $response = $this->travelOrdersService->delete($request);

        // Assertivas
        $this->assertEquals('travel_order_deleted', $response['message']);
        $this->assertEquals(200, $response['status']);
    }

    public function testListAllTravelOrdersSuccess()
    {
        $filters = ['status' => 'approved'];
        $mockedOrders = [
            (object) ['id' => 1, 'destination' => 'Paris'],
            (object) ['id' => 2, 'destination' => 'London']
        ];

        // Mockando a chamada do repositório
        $this->travelOrdersRepository->shouldReceive('listAll')
            ->once()
            ->with($filters, 'admin', 1)
            ->andReturn(collect($mockedOrders));

        // Simulando o Auth::user()
        Auth::shouldReceive('user')->once()->andReturn((object) ['user_type' => 'admin']);
        Auth::shouldReceive('id')->once()->andReturn(1);

        // Chamando o método
        $response = $this->travelOrdersService->listAll($filters);

        // Assertivas
        $this->assertCount(2, $response);
        $this->assertEquals('Paris', $response[0]->destination);
    }

    public function testUpdateStatusSuccess()
    {
        $id = '1';
        $data = ['status' => 'approved'];
        $mockedOrder = (object) [
            'id' => 1,
            'status' => (object) ['name' => 'approved'],
            'departure_date' => '2025-05-01',
            'destination' => (object) ['name' => 'Paris'],
            'user_id' => 1
        ];

        // Mockando o repositório e o serviço de notificação
        $this->travelOrdersRepository->shouldReceive('update')
            ->once()
            ->with($id, $data)
            ->andReturn($mockedOrder);
        $this->notificationService->shouldReceive('create')
            ->once()
            ->with(1, 'Your order: 1, to Paris on 01/05/2025 has been approved.');

        // Chamando o método
        $response = $this->travelOrdersService->updateStatus($id, $data);

        // Assertivas
        $this->assertEquals('status_updated_successfully', $response['message']);
        $this->assertEquals(200, $response['status']);
        $this->assertEquals($mockedOrder, $response['order']);
    }

    public function testUpdateStatusFailure()
    {
        $id = '1';
        $data = ['status' => 'approved'];

        // Simulando uma exceção no repositório
        $this->travelOrdersRepository->shouldReceive('update')
            ->once()
            ->with($id, $data)
            ->andThrow(new Exception('Database error'));

        // Esperando que a exceção seja lançada
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('unable_to_update_travel_order_status');

        // Chamando o método
        $this->travelOrdersService->updateStatus($id, $data);
    }

    public function testGetStatisticsSuccess()
    {
        $mockedStatistics = ['total_orders' => 5, 'approved_orders' => 3];

        // Mockando a chamada do repositório
        $this->travelOrdersRepository->shouldReceive('getTravelOrderStatistics')
            ->once()
            ->with('admin', 1)
            ->andReturn($mockedStatistics);

        // Simulando o Auth::user()
        Auth::shouldReceive('user')->once()->andReturn((object) ['user_type' => 'admin']);
        Auth::shouldReceive('id')->once()->andReturn(1);

        // Chamando o método
        $response = $this->travelOrdersService->getStatistics();

        // Assertivas
        $this->assertEquals($mockedStatistics, $response);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}