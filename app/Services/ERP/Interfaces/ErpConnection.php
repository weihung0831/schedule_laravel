<?php

namespace App\Services\ERP\Interfaces;

interface ErpConnection
{
    /**
     * ERP 資料庫連線名稱
     */
    public const ERP_CONNECTION = 'erp_db';

    public const ERP_PRODUCT_LINE_TABLE = 'CMSMD';

    public const ERP_MACHINE_TABLE = 'CMSMX';

    public const ERP_ORDER_TABLE = 'RA_QUERY_LACK_FROM_APS';
}
