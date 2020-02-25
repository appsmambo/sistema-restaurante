<?php
    session_start();
	error_reporting(0);
    include("../sql/conexion.php");
    $vlUser = $_SESSION["user"];

    setlocale(LC_ALL, 'es_ES');
    $vlFecha = strftime("%Y-%m-%d");

	$vgCodEmp = $_SESSION["vgCodEmp"];
    $vgCodSuc = $_SESSION["vgCodSuc"];
    $vgFlujo  = $_SESSION["flujo"];

    $ses = $_POST["ses"];
    $tipdoc = $_POST["tipdoc"];
    $tipcli = $_POST["tipcli"];
    $codcli = $_POST["codcli"];
    $nomcli = strtoupper($_POST["nomcli"]);
    $obsv = strtoupper($_POST["obsv"]);
    $total = $_POST["total"];
    $recibido = $_POST["recibido"];
    $grabacion = $_POST["grabacion"];
    $idmesa	  = $_POST["idmesa"];

    $saldo = $total - $recibido;

    //-Consultando serie y correlativo comprobante electronico--------------
    $cnrodoc     = "";
    $consulta    = mysqli_query($con, "select panexo1 as serie, pimporte2 as correlativo from man_parametros where pcodtabla='COMPR' and pcodigo='$tipdoc'");
    $rowDoc      = mysqli_fetch_array($consulta);
    $serie       = $rowDoc["serie"];
    $correlativo = $rowDoc["correlativo"] + 1;
    $cnrodoc     = $serie.'-'.str_pad($correlativo, 7, "0", STR_PAD_LEFT);
    //----------------------------------------------------------------------

    //-Consultando el PORCENTAJE del IGV------------------------------------
    $igv         = 0;
    $consulta    = mysqli_query($con, "select pimporte1 as igv from man_parametros where pcodtabla='IGV' and pcodigo='01'");
    $rowIgv      = mysqli_fetch_array($consulta);
    $vlIgv       = $rowIgv["igv"];
    //----------------------------------------------------------------------

    if(strlen($cnrodoc)<12){
        echo -1; 
    }else{
        if($tipcli==0){
            $tipito = 1;
            if(strlen($codcli) == 11){
                $tipito = 2;
            }
            $vlSqlCad0 = "insert into man_clientes(id_tipodoc, nro_doc, nombre) values ('$tipito', '$codcli', '$nomcli')";
            mysqli_query($con, $vlSqlCad0);
        }

        $porigv = 1 + ($vlIgv / 100);
        $vlImpSub = round(($total / $porigv),2);
        $vlImpIgv = $total - $vlImpSub;
        @mysqli_query($con, "SET GLOBAL time_zone = 'America/Bogota'");
        $vlSqlCad = "insert into cabecera(id_empresa, id_sucursal, id_mesa, ctipdoc, cnrodoc, cfechad, cdniruc, cnombre, cobserva, cmoneda, cimportev, crecibido, csaldo, subtotal, igv, total, cucrea, cfcrea) values ('$vgCodEmp', '$vgCodSuc', '$idmesa', '$tipdoc', '$cnrodoc', '$vlFecha', '$codcli', '$nomcli', '$obsv', 'S', '$total',  '$recibido',  '$saldo', '$vlImpSub', '$vlImpIgv', '$total', '$vlUser', now())";
        $rpta1 = mysqli_query($con, $vlSqlCad);
        if($rpta1<=0){
            echo $rpta1;
        }else{
            //-Consultando el ultimo id de la cabecera------------------------------
            $idCab       = 0;
            $consulta    = mysqli_query($con, "select cid from cabecera where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and ctipdoc='$tipdoc' and cnrodoc='$cnrodoc' and cdniruc='$codcli'");
            $rowDoc      = mysqli_fetch_array($consulta);
            $idCab       = $rowDoc["cid"];
            //----------------------------------------------------------------------
            
            if($idCab<=0){
                //-Eliminando cabecera--------------------------------------------------
                $vlSqlCad2 = "delete from cabecera where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and ctipdoc='$tipdoc' and cnrodoc='$cnrodoc' and cdniruc='$codcli'";
                mysqli_query($con, $vlSqlCad2);
                //----------------------------------------------------------------------

                echo 0;
            }else{

                //-Grabando detalle-----------------------------------------------------
                if($vgFlujo==1){
                    $consulta = mysqli_query($con, "select * from tmp_pedidos where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$idmesa' and estado<>9 order by id");
                }

                if($vgFlujo==2){
                    $consulta = mysqli_query($con, "select * from tmp_pedidos where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$idmesa' and estado=3 order by id");
                }

                if (mysqli_num_rows($consulta) > 0){
                    while ($row = mysqli_fetch_array($consulta)){
                        $vlCod  = $row["id_producto"];
                        $vlNom  = $row["descripcion"];
                        $vlCom  = $row["comentario"];
                        $vlCan  = $row["cant"];
                        
                        $vlPre  = $row["precio"];
                        $vlTot  = $vlCan * $vlPre;

                        $vlIgvv = round(($vlTot * $vlIgv) / 100,2);
                        $vlSub  = $vlTot - $vlIgvv;

                        $vlSqlCad = "insert into detalle(didc, id_empresa, id_sucursal, id_mesa, dtipdoc, dnrodoc, dfechad, dcodigo, dcantidad, dmoneda, dpreuni, dsubtot, digv, dtotal, destado, ducrea, dfcrea) values('$idCab', '$vgCodEmp', '$vgCodSuc', '$idmesa', '$tipdoc', '$cnrodoc', '$vlFecha', '$vlCod', '$vlCan', 'S', '$vlPre', '$vlSub', '$vlIgvv',  '$vlTot', '', '$vlUser', now())";
                        $rpta1 = mysqli_query($con, $vlSqlCad);
                    }

                    //-Grabando Pagos-------------------------------------------------------
                    $consulta = mysqli_query($con, "select * from tmp_pagos where sesion='$ses' order by cod_secuencia");
                    if (mysqli_num_rows($consulta) > 0){
                        while ($row = mysqli_fetch_array($consulta)){
                            $vlTipPag = $row["tipo_pago"];
                            $vlImpSol = $row["importe_soles"];
                            $vlImpDol = $row["importe_dolares"];
                            $vlTipCam = $row["tcambio"];
                            $vlObserv = strtoupper($row["observacion"]);
                            $vlCodPag = $row["cod_pago"];
                            $vlNomPag = $row["nom_pago"];
                            $vlSqlCad = "insert into pagos(id_empresa, id_sucursal, fidc, ffechad, ffpago, fsoles, fdolar, ftcambio, fobserva, cod_pago, nom_pago, user_registro) values('$vgCodEmp', '$vgCodSuc', '$idCab', '$vlFecha', '$vlTipPag', '$vlImpSol', '$vlImpDol', '$vlTipCam', '$vlObserv', '$vlCodPag', '$vlNomPag', '$vlUser')";
                            mysqli_query($con, $vlSqlCad);
                        }
                    }
                    //----------------------------------------------------------------------

                    //-Actualizando ultimo correlativo--------------------------------------
                    $vlSqlCad2 = "update man_parametros set pimporte2='$correlativo' where pcodtabla='COMPR' and pcodigo='$tipdoc'";
                    mysqli_query($con, $vlSqlCad2);
                    //----------------------------------------------------------------------

                    if($vgFlujo==1){
                        //-Actualizando el estado de la mesa------------------------------------
                        $vlSqlCad = "update man_mesas set ocupado=0, user_ocupado='', fec_ocupado='0000-00-00' where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$idmesa'";
                        mysqli_query($con, $vlSqlCad);
                        //----------------------------------------------------------------------
                    
                        
                        //-Eliminando Carrito compra Temporal-----------------------------------
                        $vlSqlCad = "update tmp_pedidos set estado=9 where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$idmesa' and estado<>9";
                        mysqli_query($con, $vlSqlCad);
                        //----------------------------------------------------------------------
                    }

                    if($vgFlujo==2){
                        //-Actualizando el estado de la mesa------------------------------------
                        $vlSqlCad = "update man_mesas set ocupado=1 where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$idmesa'";
                        mysqli_query($con, $vlSqlCad);
                        //----------------------------------------------------------------------
                    
                        
                        //-Eliminando Carrito compra Temporal-----------------------------------
                        $vlSqlCad = "update tmp_pedidos set estado=1 where id_empresa='$vgCodEmp' and id_sucursal='$vgCodSuc' and id_mesa='$idmesa' and estado=3";
                        mysqli_query($con, $vlSqlCad);
                        //----------------------------------------------------------------------
                    }

                    //-Eliminando Pagos Temporal--------------------------------------------
                    $vlSqlCad4 = "delete from tmp_pagos where sesion='$ses'";
                    mysqli_query($con, $vlSqlCad4);
                    //----------------------------------------------------------------------

                    echo '1|'.$idCab;

                }else{
                    echo 0;
                }
                //----------------------------------------------------------------------
            }
        }
    }
?>