<div class="recibo well" id="blockchain" style="width: 100%;text-align: center">
    <form action="#" id="recibo-pago">
        <input type="hidden" name="id" class="hide" value="<?=$id?>" />

    <h3>Pulsa en la imagen para redirigirte a confirmar el pago.</h3>
        <fieldset class="well" style="text-align: center">
            <img src="<?=$qr;?>"  alt="qr" width="80%" style="cursor:pointer;"
                 onclick="location.href='bitcoin:<?=$direccion?>'" />
        </fieldset>
        <p>dirección: </p>
        <textarea id="dir" name="code" readonly><?=$direccion?></textarea>
        <hr/>
        <input type="submit" class="btn btn-success" value="Copiar dirección" />
    </form>
    <hr/>
    <h4>Si deseas copiar la dirección pulsa el boton.</h4>
</div>
<style>
    #dir{   
        width:100%;
        font-size: 1.3em;
        text-align: center;
        border:thin solid #c0c0c0;
        resize: none;
    }
</style>
<script type="text/javascript">
    $("input[type=submit]").click(copiar);
    function copiar(evt){
        var temp = $("<input>");
        //lo agregamos a nuestro body
        $("body").append(temp);
        //agregamos en el atributo value del input el contenido html encontrado
        //en el td que se dio click
        //y seleccionamos el input temporal
        temp.val("<?=$direccion;?>").select();
        //ejecutamos la funcion de copiado
        document.execCommand("copy");
        //eliminamos el input temporal
        temp.remove();
        console.log("texto copiado : ["+document.queryCommandSupported('copy')+"]");
    }
</script>