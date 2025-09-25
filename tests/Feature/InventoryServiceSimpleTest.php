<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\InventoryService;

class InventoryServiceSimpleTest extends TestCase
{
    use RefreshDatabase;

    public function test_inventory_service_can_be_instantiated()
    {
        $service = new InventoryService();
        $this->assertInstanceOf(InventoryService::class, $service);
    }

    public function test_inventory_service_has_required_methods()
    {
        $service = new InventoryService();
        
        $this->assertTrue(method_exists($service, 'stockIn'));
        $this->assertTrue(method_exists($service, 'stockOut'));
        $this->assertTrue(method_exists($service, 'stockAdjustment'));
        $this->assertTrue(method_exists($service, 'transferStock'));
        $this->assertTrue(method_exists($service, 'getStockBalance'));
        $this->assertTrue(method_exists($service, 'getStockHistory'));
        $this->assertTrue(method_exists($service, 'validateStockIntegrity'));
        $this->assertTrue(method_exists($service, 'reconcileStock'));
    }

    public function test_inventory_service_constants_are_defined()
    {
        $this->assertEquals(1, InventoryService::MOVE_TYPE_STOCK_IN);
        $this->assertEquals(2, InventoryService::MOVE_TYPE_STOCK_OUT);
        $this->assertEquals(3, InventoryService::MOVE_TYPE_ADJUSTMENT);
    }
}
