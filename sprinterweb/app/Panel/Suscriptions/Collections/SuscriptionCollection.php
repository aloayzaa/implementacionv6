<?php

namespace App\Panel\Suscriptions\Collections;

use App\PaymentMethod\Entities\PaymentMethod;
use App\Plan;

/**
 * Created by PhpStorm.
 * User: anikamagroup
 * Date: 11/02/19
 * Time: 06:22 PM
 */
class SuscriptionCollection
{
    public function actions($subscriptions)
    {
        foreach ($subscriptions as $subscription) {

            if ($subscription->sus_estado == 1) {
                $subscription->sus_estado = '<span class="label label-success">Activo</span>';
            } elseif ($subscription->sus_estado == 0) {
                $subscription->sus_estado = '<span class="label label-danger">Desactivado</span>';
            } elseif ($subscription->sus_estado == 2) {
                $subscription->sus_estado = '<span class="label label-warning">Pendiente</span>';
            }

            $fechai = $newDate = date("d-m-Y", strtotime($subscription->sus_fechainicio));
            $fechaf = $newDate = date("d-m-Y", strtotime($subscription->sus_fechafin));

            $subscription->periodo = $fechai . ' - ' . $fechaf;

            $plan = Plan::findOrFail($subscription->pla_id);
            $subscription->plan = $plan->pla_descripcion;

            if ($subscription->sus_comprobante) {
                $subscription->comprobante = '<a href="#" data-toggle="modal" data-target="#modalVoucher">
                                            <img src="' . asset('img/lupa.png') . '">
                                          </a>
                                          
                      <div class="modal fade" id="modalVoucher" tabindex="-1">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header label-default">
                                <h5 class="modal-title text-white" id="exampleModalLabel">
                                Comprobante
                                <i class="material-icons">insert_photo</i>
                                </h5>
                                <a type="button" class="btnModalClose" data-dismiss="modal">
                                <i class="material-icons">cancel</i>
                                </a>
                              </div>
                              <div class="modal-body contenedorModal">
                                    <img src="' . "/comprobantes/" . $subscription->sus_comprobante . '">
                              </div>
                            </div>
                          </div>
                        </div>';
            } else {
                $subscription->comprobante = '';
            }
        }
    }
}
