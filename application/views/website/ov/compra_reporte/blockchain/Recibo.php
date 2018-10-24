<div class="recibo well" id="blockchain" style="width: 100%;text-align: center">
    <h3>Pulsa en la imagen para redirigirte a confirmar el pago.</h3>
    <fieldset class="well" style="text-align: center">
        <img src="/template/img/favicon/favicon.png" alt="qr" width="80%" onclick="location.href='bitcoin:<?=$direccion?>'" />
    </fieldset>
    <h2 id="dir"><?=$direccion?></h2>
    <hr/>
    <input type="submit" class="btn btn-success" value="Copiar dirección" />
    <hr/>
    <h4>Si deseas copiar la dirección pulsa el boton.</h4>
</div>
<script type="text/javascript">
    $("input[type=submit]").click(copiar);
    function copiar(evt){
        var aux = document.createElement("input");
        aux.setAttribute("value",'<?=$direccion?>');
        document.body.appendChild(aux);
        aux.select();
        document.execCommand("copy");
        document.body.removeChild(aux);
        console.log("texto copiado : ["+document.queryCommandSupported('copy')+"]");
    }
</script>