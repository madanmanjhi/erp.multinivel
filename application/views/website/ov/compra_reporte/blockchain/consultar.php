<div id="spinner-div"></div>
<div>
    <legend>Total a Pagar</legend>
    <fieldset class="col col-md-12">
        <div class="row">
            <div class="col-md-6">
                <div class="backHome bg-color-grayDark">
                    <h3><strong><?=$currency?>: </strong></h3>
                    <h2 class="no-padding">$ <?=$value?></h2>
                </div>
            </div>
            <div class="col-md-6">
                <div class="backHome bg-color-green" style="text-align: right">
                    <h3 ><strong><?=$xe?>: </strong></h3>
                    <h2 class="no-padding" ><?=$amount; ?></h2>
                </div>
            </div>
        </div>
        <hr class="col-md-11"/>
        <div class="row">
            <legend>Tasa(s) de cambio</legend>
                <?php foreach ($rates as $cur => $ticker) : ?>
                    <?php $sym = $ticker->symbol; $cuk= " " . $cur; ?>
                    <div class="backHome">
                        <div class="col-md-9" >
                            <?=$sym.round($ticker->m15,2).$cuk; ?>
                        </div>
                        <div class="col-md-3" ><b>1 <?=$xe?></b></div>
                        <div class="col-md-12" >
                            <table>
                                <tr>
                                    <td>precio: <?=  $sym.round($ticker->last,2).$cuk; ?></td>
                                    <td>compra: <?= $sym.round($ticker->buy,2).$cuk; ?></td>
                                    <td>venta: <?= $sym.round($ticker->sell,2).$cuk; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                <?php endforeach; ?>
        </div>

    </fieldset>
</div>
<hr class="col-md-11"/>
<div class="row">
    <section class="col-md-12">
        <h2 style="text-align: center">
            ¿Deseas proceder con el pago? <br/> haz click en
            <strong class="txt-color-green">continuar</strong>.
        </h2>
    </section>
</div>
<style type="text/css">
    td {
        text-align: left;
    }
    div.backHome{
        height: auto !important;
        min-height: 3em;
    }
    .backHome td{
        font-size: x-small;
        padding-right: 1rem;
    }
</style>


