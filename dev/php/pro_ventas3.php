<?php
    session_start();
    include("sys_seguridad.php");
    include("../sql/conexion.php");
    $campo  = $_GET["campo"];
    $valor  = $_GET["valor"];
    $canlet = strlen($valor);

    @mysqli_query($con, "SET NAMES 'utf8'");
    if($campo=="" && $valor==""){
        $consulta = mysqli_query($con, "select codigo, nombre, pv_soles, pv_libreria, estado, stock, imagen, autores from man_titulos order by vendidos desc, nombre asc limit 10");
    }else{
        $vlWhere = " where nombre like '%$valor%'";
        if($campo=="ISBN"){
            $vlWhere = " where cod_barra='$valor'";
        }
        $consulta = mysqli_query($con, "select codigo, nombre, pv_soles, pv_libreria, estado, stock, imagen, autores from man_titulos".$vlWhere." order by vendidos desc, nombre asc");
    }
    
    if (mysqli_num_rows($consulta) <= 0){
    ?>
        <br><h4 style="color:#FF0000; text-align:center"><i class="icon fa fa-ban"></i> No se encontraron libros</h4><br>
    <?php
    }else{
?>
    <div class="box-body">
        <table id="example1" class="table table-bordered table-hover">
            <thead>
                <tr style="background-color:#008442; color:#FFFFFF;">
                    <th>Imagen</th>
                    <th>Descripcion</th>
                    <th style="width:80px; text-align:right;">Precio Feria</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
        <?php
            $itm = 0;
            while ($row = mysqli_fetch_array($consulta)){
                $itm = $itm + 1;
                $codigo = $row["codigo"];
                $stock  = $row["stock"];

                $vlBlocked = "";
                $nombre_fichero = "../dist/img/titulos/".trim($row["imagen"]);

                $imagen = "sin_foto.jpg";
                if(is_file($nombre_fichero)) {
                    $imagen = $row["imagen"];
                }
                
                $colorLet = "#FD5031";
                if($row["stock"]>0){
                    $colorLet = "";
                }else{
                    //$vlBlocked = "disabled";
                }

                
                if($id > 0){
                    $enc = strpos(trim($vgCab_Cad), trim($row["codigo"]));
                    if($enc !== false){
                        $vlBlocked = "disabled";
                    }
                }

        ?>
            <tr style="color:<?php echo $colorLet;?>">
                <td><img src="../dist/img/titulos/<?php echo $imagen;?>" style="width:40px;height:auto;"></td>
                <td>                                        
                <?php echo $row["nombre"]."<br>";?>
                <strong style="color:#0000FF"><?php echo $row["autores"]."</strong><br>";?>
                <strong>Stock: <span id="lblStock<?php echo $row["codigo"];?>"><?php echo $row["stock"];?></span>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspPrecio Normal: S/ <?php echo number_format($row["pv_libreria"], 2, '.', ',');?></strong>
                </td>
                <td style="text-align:right;">S/ <?php echo $row["pv_soles"];?></td>
                <td><button type="button" id="btnAgregar<?php echo $row["codigo"];?>" name="btnAgregar<?php echo $row["codigo"];?>" class="btn btn-block btn-success btn-lg" onClick="javascript:agregar('<?php echo $row["codigo"];?>');" <?php echo $vlBlocked;?> >AGREGAR</button><input type="hidden" id="txtNom<?php echo $row["codigo"];?>" name="txtNom<?php echo $row["codigo"];?>" value="<?php echo $row["nombre"];?>">
                <input type="hidden" id="txtPre<?php echo $row["codigo"];?>" name="txtPre<?php echo $row["codigo"];?>" value="<?php echo $row["pv_soles"];?>">
                <input type="hidden" id="txtPreLib<?php echo $row["codigo"];?>" name="txtPreLib<?php echo $row["codigo"];?>" value="<?php echo $row["pv_libreria"];?>">               
                <input type="hidden" id="txtStock_<?php echo $row["codigo"];?>" name="txtStock_<?php echo $row["codigo"];?>" value="<?php echo $row["stock"];?>"></td>
            </tr>
<script language="JavaScript">
    if(document.getElementById("txtTipBus").value != "ISBN"){
        var cadena = document.getElementById("txtCadenaCodigo").value;
        var hay    = cadena.search("<?php echo $row["codigo"];?>");
        if(hay<0){
        }else{
            document.getElementById("btnAgregar<?php echo $row["codigo"];?>").disabled = "true";
            document.getElementById("txtStock_<?php echo $row["codigo"];?>").value = document.getElementById("txtStock<?php echo $row["codigo"];?>").value;
            document.getElementById("lblStock<?php echo $row["codigo"];?>").innerHTML = document.getElementById("txtStock_<?php echo $row["codigo"];?>").value;
        }
    }
</script>
        <?php }?>
            </tbody>
        </table>
    </div>

<?php }?>
<!-- DataTables -->
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#example1').DataTable( {
        "scrollY":        "405px",
        "scrollCollapse": true,
        "searching":        false,
        "ordering":       false,
        "paging":         false
    });
});
document.getElementById("txtBuscar").value = "";
if(document.getElementById("txtTipBus").value == "ISBN"){
    <?php if($itm==1 && $canlet==13){?>
        document.getElementById("btnAgregar<?php echo $codigo;?>").disabled = "true";
        var cadena = document.getElementById("txtCadenaCodigo").value;
        var hay    = cadena.search("<?php echo $codigo;?>");
        if(hay<0){
            <?php //if($stock>0){ ?>
                agregar("<?php echo $codigo;?>");
            <?php //}?>
        }else{
            codigo = "txtCodSec<?php echo $codigo;?>";
            codsec = parseFloat(document.getElementById(codigo).value);

            sumar("<?php echo $codigo;?>",codsec);
        }
        
    <?php }?>
}
</script>