<?php

namespace SmartCms\FastOrders\Admin\FastOrderResource\Pages;

use Filament\Resources\Pages\ListRecords;
use SmartCms\FastOrders\Admin\Resources\FastOrderResource;

class ListFastOrders extends ListRecords
{
   protected static string $resource = FastOrderResource::class;
}
