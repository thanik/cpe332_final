<?php
class Purchase extends Eloquent {
	protected $table = 'purchases';
	public $timestamps = false;
	public $primaryKey = 'InvoiceNo';
}