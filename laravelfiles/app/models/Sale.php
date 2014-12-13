<?php
class Sale extends Eloquent {
	protected $table = 'sales';
	public $timestamps = false;
	public $primaryKey = 'InvoiceNo';
}