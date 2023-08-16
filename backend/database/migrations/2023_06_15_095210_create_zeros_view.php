<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE VIEW `zeros` AS select `posbackend`.`products`.`id` AS `id`,`posbackend`.`products`.`item_id` AS `item_id`,`posbackend`.`products`.`efriscategory` AS `efriscategory`,`posbackend`.`products`.`product_name` AS `product_name`,`posbackend`.`products`.`product_code` AS `product_code`,`posbackend`.`products`.`quantity` AS `quantity`,`posbackend`.`products`.`minimum_quantity` AS `minimum_quantity`,`posbackend`.`products`.`unit_id` AS `unit_id`,`posbackend`.`products`.`selling_price` AS `selling_price`,`posbackend`.`products`.`reserve_price` AS `reserve_price`,`posbackend`.`products`.`wholesale_unitprice` AS `wholesale_unitprice`,`posbackend`.`products`.`wholesale_reserveprice` AS `wholesale_reserveprice`,`posbackend`.`products`.`is_product` AS `is_product`,`posbackend`.`products`.`vat` AS `vat`,`posbackend`.`products`.`status` AS `status`,`posbackend`.`products`.`branch_id` AS `branch_id`,`posbackend`.`products`.`business_id` AS `business_id`,`posbackend`.`products`.`user_id` AS `user_id`,`posbackend`.`products`.`created_at` AS `created_at`,`posbackend`.`products`.`updated_at` AS `updated_at` from `posbackend`.`products` where ((`posbackend`.`products`.`quantity` = 0) and (`posbackend`.`products`.`business_id` = 1))");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS `zeros`");
    }
};
