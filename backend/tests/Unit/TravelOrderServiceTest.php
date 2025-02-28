<?php

namespace Tests\Unit;

use Tests\TestCase;
use Mockery;
use Exception;
use App\Models\TravelOrder;
use App\Services\TravelOrders\TravelOrdersService;
use App\Repositories\TravelOrders\TravelOrdersRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TravelOrdersServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $travelOrderService;
    protected $travelOrdersRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->travelOrdersRepository = Mockery::mock(TravelOrdersRepositoryInterface::class);
        $this->travelOrderService = new TravelOrdersService($this->travelOrdersRepository);
    }

    /** @test */
    public function it_creates_a_travel_order_successfully()
    {
        Auth::shouldReceive('id')->andReturn(1);

        $data = [
            'origin' => 'São Paulo',
            'destination' => 'Rio de Janeiro',
            'departure_date' => now()->addDays(2)->toDateString(),
            'return_date' => now()->addDays(5)->toDateString(),
        ];

        $mockOrder = new TravelOrder($data);

        $this->travelOrdersRepository
            ->shouldReceive('create')
            ->once()
            ->with(Mockery::subset($data))
            ->andReturn($mockOrder);

        $response = $this->travelOrderService->create($data);

        $this->assertEquals(201, $response['status']);
        $this->assertEquals('travel_order_created_successfully', $response['message']);
        $this->assertInstanceOf(TravelOrder::class, $response['order']);
    }

    /** @test */
    public function it_updates_a_travel_order_successfully()
    {
        $id = '12345';
        $data = ['destination' => 'Salvador'];

        $mockOrder = new TravelOrder(array_merge(['id' => $id], $data));

        $this->travelOrdersRepository
            ->shouldReceive('update')
            ->once()
            ->with($id, $data)
            ->andReturn($mockOrder);

        $response = $this->travelOrderService->update($data, $id);

        $this->assertEquals(200, $response['status']);
        $this->assertEquals('travel_order_updated_successfully', $response['message']);
        $this->assertInstanceOf(TravelOrder::class, $response['order']);
    }

    /** @test */
    public function it_deletes_a_travel_order_successfully()
    {
        $this->travelOrdersRepository
            ->shouldReceive('delete')
            ->once()
            ->with('12345')
            ->andReturn(true);

        $response = $this->travelOrderService->delete(['id' => '12345']);

        $this->assertEquals(200, $response['status']);
        $this->assertEquals('travel_order_deleted', $response['message']);
    }

    /** @test */
    public function it_lists_all_TravelOrders()
    {
        $filters = ['status_id' => 1];

        $mockOrders = collect([
            new TravelOrder(['id' => 1, 'origin' => 'São Paulo', 'destination' => 'Rio']),
            new TravelOrder(['id' => 2, 'origin' => 'Curitiba', 'destination' => 'Florianópolis']),
        ]);

        $this->travelOrdersRepository
            ->shouldReceive('listAll')
            ->once()
            ->with($filters)
            ->andReturn($mockOrders);

        $orders = $this->travelOrderService->listAll($filters);

        $this->assertIsArray($orders);
        $this->assertCount(2, $orders);
    }
}
