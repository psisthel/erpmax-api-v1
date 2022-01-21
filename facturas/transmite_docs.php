<?php

$servername = "107.180.46.150";
$username = "demo_pe";
$password = "d5xkWMc@WGly";
$dbname = "erpmax_demo_pe";

// $servername = "107.180.46.150";
// $username = "sisthel_prd";
// $password = "dRfg5WcrVbA6";
// $dbname = "sisthel_prd";

$conn = new mysqli($servername, $username, $password, $dbname);

if (mysqli_connect_errno()) {
    printf("Conexión fallida: %s\n", mysqli_connect_error());
    exit();
}

if( isset( $_POST['filial'] ) && isset( $_POST['documento'] ) ) {

    $ret_nfe = array();

    $filial = $_POST['filial'];
    $documento = $_POST['documento'];

    $ccaminho = realpath(dirname(__FILE__) . '/../../..') . '/nfe/' . $filial . '/';

    $ccodecli = "";
    $ninafecta = 0;
    $ctpdeigv = "1";
    $cagencia = "";
    $ccuenta = "";
    $cctaCCI = "";
    $cSWIFT = "";
    $cobservaciones = "";
    $cdetraccion = "false";
    $cmail_directo = "";

    $c_a0_codigo = "";
    $c_a0_nome = "";
    $c_a0_endereco = "";
    $c_a0_compend = "";
    $c_a0_bairro = "";
    $c_a0_cidade = "";
    $c_a0_uf = "";
    $c_a0_cep = "";
    $c_a0_cgc = "";
    $c_a0_nomfant = "";
    $c_a0_fone1 = "";
    $c_a0_fone2 = "";
    $c_a0_mail = "";
    $c_a0_envmail = "N";
    $lemail_pse = "true";
    $nperc_detraccion = 0;
    $cdetraccion_tipo = "";
    $cdetraccion_medio_de_pago = "";

    $lcontinua = false;

    $csql = "SELECT A0_CODIGO,A0_NOME,A0_ENDERECO,A0_COMPEND,A0_BAIRRO,A0_CIDADE,A0_UF,A0_CEP,A0_CGC,A0_NOMFANT,A0_FONE1,A0_FONE2,A0_EMAIL,A0_ENVMAILDF";
    $csql = $csql . "  FROM pa0990";
    $csql = $csql . " WHERE A0_CODIGO='" . $filial . "'";

    if ( $res_a0 = $conn->query($csql) ) {

        $row_a0 = $res_a0->fetch_assoc();

        $c_a0_codigo = $row_a0['A0_CODIGO'];
        $c_a0_nome = $row_a0['A0_NOME'];
        $c_a0_endereco = $row_a0['A0_ENDERECO'];
        $c_a0_compend = $row_a0['A0_COMPEND'];
        $c_a0_bairro = $row_a0['A0_BAIRRO'];
        $c_a0_cidade = $row_a0['A0_CIDADE'];
        $c_a0_uf = $row_a0['A0_UF'];
        $c_a0_cep = $row_a0['A0_CEP'];
        $c_a0_cgc = $row_a0['A0_CGC'];
        $c_a0_nomfant = $row_a0['A0_NOMFANT'];
        $c_a0_fone1 = $row_a0['A0_FONE1'];
        $c_a0_fone2 = $row_a0['A0_FONE2'];
        $c_a0_mail = $row_a0['A0_EMAIL'];
        $c_a0_envmail = $row_a0['A0_ENVMAILDF'];
    }	

    if($c_a0_envmail=='S') {
        $lemail_pse = "false";
    }

    $csql = "SELECT B5_CLIENTE";
    $csql = $csql . "  FROM pb5990";
    $csql = $csql . " WHERE B5_SITUACAO='0'";
    $csql = $csql . "   AND B5_FILIAL='" . $filial . "'";
    $csql = $csql . "   AND B5_CODIGO='" . $documento . "'";

    if ( $res_b5 = $conn->query($csql) ) {
        $row_b5 = $res_b5->fetch_assoc();
        $ccodecli = $row_b5['B5_CLIENTE'];
    }

    $csql = "SELECT A1_CODIGO,A1_TPDOC,A1_RG,A1_CPFCGC,A1_CEP,A1_ENDERECO,A1_UF,A1_CIDADE,A1_BAIRRO,A1_NOME,A1_FANTASIA,A1_TELEFONE,A1_PAGWEB,A1_EMAIL,";
    $csql = $csql . "A1_NRO,A1_COMPEND,A1_EMAIL2";
    $csql = $csql . "  FROM pa1990";
    $csql = $csql . " WHERE A1_SITUACAO<>'9'";
    $csql = $csql . "   AND A1_FILIAL='" . $filial . "'";
    $csql = $csql . "   AND A1_CODIGO='" . $ccodecli . "'";

    if ( $res_a1 = $conn->query($csql) ) {

        $row_a1 = $res_a1->fetch_assoc();

        $cDepaCli = $row_a1['A1_UF'];
        $cTipoDoc = $row_a1['A1_TPDOC'];
        $cRUCCli = $row_a1['A1_CPFCGC']; 
        $cDNICli = $row_a1['A1_RG']; 
        $cRazaoCli = $row_a1['A1_NOME']; 
        $cEndCli = trim($row_a1['A1_ENDERECO']) . " " . trim($row_a1['A1_NRO']) . " " . trim($row_a1['A1_COMPEND']); 
        $cProvCli = $row_a1['A1_CIDADE'];
        $cDistCli = $row_a1['A1_BAIRRO'];
        $cMailCli = $row_a1['A1_EMAIL'];
        $c2MailCli = $row_a1['A1_EMAIL2'];

        if(trim($cDepaCli)=="EX") {
            $sunat_transaction = 2;
            $ctpdeigv = "16";
        } else {
            $sunat_transaction = 1;
            $ctpdeigv = "1";
        }
        
        if($cTipoDoc=="6") {
            $cliente_numero_documento = $cRUCCli;
        } else {
            $cliente_numero_documento = $cDNICli;
        }
        
        $cDepAntCli = $cDepaCli;
        $cPrvAntCli = $cProvCli;
        $cDisAntCli = $cDistCli;
        
        $csql = "SELECT ZK_DESCRICAO";
        $csql = $csql . " FROM pzk990";
        $csql = $csql . " WHERE ZK_FILIAL='" . $filial . "'";
        $csql = $csql . "   AND ZK_VALOR='" . $cDepAntCli . "'";
        $csql = $csql . "   AND ZK_TABELA='T18'";
        $csql = $csql . "   AND ZK_SITUACAO<>'9'";
        
        if ( $res_zk = $conn->query($csql) ) {
            $row_zk = $res_zk->fetch_assoc();
            $cDepaCli = trim($row_zk['ZK_DESCRICAO']);
        }	

        $csql = "SELECT AV_DESC";
        $csql = $csql . " FROM pav990";
        $csql = $csql . " WHERE AV_DPTO='" . $cDepAntCli . "'";
        $csql = $csql . "   AND AV_PROV='" . $cPrvAntCli . "'";

        if ( $res_av = $conn->query($csql) ) {
            $row_av = $res_av->fetch_assoc();
            $cProvCli = trim($row_av['AV_DESC']);
        }	

        $csql = "SELECT AW_DESC";
        $csql = $csql . " FROM paw990";
        $csql = $csql . " WHERE AW_DPTO='" . $cDepAntCli . "'";
        $csql = $csql . "   AND AW_PROV='" . $cPrvAntCli . "'";
        $csql = $csql . "   AND AW_DISTRI='" . $cDisAntCli . "'";
        
        if ( $res_aw = $conn->query($csql) ) {
            $row_aw = $res_aw->fetch_assoc();
            $cDistCli = trim($row_aw['AW_DESC']);
        }
        
        $lcontinua = true;

    } else {

        $item = array(
            "estado" => "404",
            "msg" => "¡cliente inexistente o bloqueado!",
        );

        $lcontinua = false;

    } 


    if($lcontinua) {

        $csql = "SELECT B5_CLIENTE,B5_SERIE,B5_NFISCAL,B5_TOTAL,B5_EMISSAO,B5_BASIMP2,";
        $csql = $csql . "B5_VALIMP2,B5_MOEDA,B5_TPDOC,B5_FORMAPAG,B5_TPOPER,";
        $csql = $csql . "B5_DESCONT,B5_DESC,B5_BASEICMS,B5_IMPOSTOS,B5_BASIMP3,";
        $csql = $csql . "B5_VALIMP3,B5_TAXA,B5_TOTAL,B5_INAFECTA,B5_OBS1,";
        $csql = $csql . "B5_OBS2,B5_VCMTO,B5_MAILSTD";
        $csql = $csql . "  FROM pb5990";
        $csql = $csql . " WHERE B5_SITUACAO='0'";
        $csql = $csql . "   AND B5_FILIAL='" . $filial . "'";
        $csql = $csql . "   AND B5_CODIGO='" . $documento . "'";

        if ( $res_b5 = $conn->query($csql) ) {

            $row_b5 = $res_b5->fetch_assoc();

            $cmail_directo = $row_b5['B5_MAILSTD'];
            
            if(!empty($cmail_directo)) {
                $cMailCli = $cmail_directo;
            }
            
            $fecha_emision = substr($row_b5['B5_EMISSAO'],0,4).substr($row_b5['B5_EMISSAO'],4,2).substr($row_b5['B5_EMISSAO'],6,2);
            
            $tpdoc1 = $row_b5['B5_TPDOC'];
            if($tpdoc1=="01") {
                $ctpdoc = 1;
            } else {
                $ctpdoc = 2;
            }
            $cSerie2 = $row_b5['B5_SERIE'];
            $cnf = $row_b5['B5_NFISCAL'];
            $dEmissao = substr($row_b5['B5_EMISSAO'],0,4)."-".substr($row_b5['B5_EMISSAO'],4,2)."-".substr($row_b5['B5_EMISSAO'],6,2);
            $dVencimento = substr($row_b5['B5_VCMTO'],0,4)."-".substr($row_b5['B5_VCMTO'],4,2)."-".substr($row_b5['B5_VCMTO'],6,2);
            //$dEmissao = "2020-04-18";
            $cmoeda = (int) $row_b5['B5_MOEDA'];
            $ndesc = number_format( $row_b5['B5_DESCONT'],2 );
            
            if($row_b5['B5_BASEICMS']>0) {					// B5_BASEICMS
                $nbase = str_replace(",","",number_format( $row_b5['B5_BASEICMS'],2) );
                $nvaligv = $row_b5['B5_IMPOSTOS'];			// B5_IMPOSTOS
                $ntotal =  str_replace(",","",number_format( ( $row_b5['B5_TOTAL']+$nvaligv ) ,2 ));
            } else {
                $nbase = str_replace(",","", number_format( $row_b5['B5_BASIMP2'],2 ));
                $nvaligv =  str_replace(",","",number_format( $row_b5['B5_VALIMP2'],2 ));
                $ntotal =  str_replace(",","",number_format( $row_b5['B5_TOTAL'] ,2 ));
            }
            
            if($ors->fields['B5_INAFECTA']>0) {		// INAFECTA
                $ninafecta = str_replace(",","", number_format( $row_b5['B5_INAFECTA'],2 ));
                $nvaligv =  str_replace(",","",number_format( 0,2 ));
                $ntotal =  str_replace(",","",number_format( $row_b5['B5_TOTAL'] ,2 ));
            }
            
            $ntxmoeda = number_format($row_b5['B5_TAXA'],4);
            $cformapag = $row_b5['B5_FORMAPAG'];
            $lcredito = "N";

            if($row_b5['B5_BASIMP3']>0) {		// DETRACCION
                
                $cdetraccion = "true";
                $sunat_transaction = 16;
                
                $csql = "SELECT B7_ALQIMP3";
                $csql = $csql . "  FROM pb7990";
                $csql = $csql . " WHERE B7_SITUACAO<>'9'";
                $csql = $csql . "   AND B7_FILIAL='" . $filial . "'";
                $csql = $csql . "   AND B7_CODIGO='" . $documento . "'";

                if ( $res_b7 = $conn->query($csql) ) {

                    $row_b7 = $res_b7->fetch_assoc();

                    $nperc_detraccion = $row_b7['B7_ALQIMP3'];
                    $nperc_detraccion = str_replace(",","",number_format($nperc_detraccion,2));
                    $cdetraccion_tipo = "20";
                    $cdetraccion_medio_de_pago = "1";
                }
                
            } else {
                $cdetraccion = "false";
            }

            $csql = "SELECT AD_NOMEAGENCIA,AD_CONTA,AD_SWIFT,AD_CCI";
            $csql .= " FROM pad990";
            $csql .= " WHERE AD_FILIAL='" . $filial . "'";
            $csql .= "   AND AD_TIPO='0'";

            if($cmoeda==1) {                        // SOLES
                $csql .= " AND AD_MOEDA='001'";
            }
            if($cmoeda==2) {                        // DOLARES
                $csql .= " AND AD_MOEDA='002'";
            }
            if($cmoeda==3) {                        // EUROS
                $csql .= " AND AD_MOEDA='003'";
            }

            if ( $res_ad = $conn->query($csql) ) {

                $row_ad = $res_ad->fetch_assoc();

                $cagencia = trim($row_ad['AD_NOMEAGENCIA']);
                $ccuenta = trim($row_ad['AD_CONTA']);
                $cctaCCI = trim($row_ad['AD_CCI']);
                if(trim($cDepAntCli)=="EX") {
                    $cSWIFT = trim($row_ad['AD_SWIFT']);
                }
                $cobservaciones = $cagencia . "\nCC: " . $ccuenta . "\nCCI: " . $cctaCCI;
                if(!empty($cSWIFT)) {
                    $cobservaciones .= "\nSWIFT: " . $cSWIFT;
                }
            }
                
            $cqry = "SELECT AM_DESCRICAO,AM_CREDITO";
            $cqry = $cqry . "  FROM pam990 ";
            $cqry = $cqry . " WHERE AM_FILIAL='" . $filial . "'";
            $cqry = $cqry . "   AND AM_CODIGO='" . $cformapag . "'";

            if ( $res_am = $conn->query($cqry) ) {

                $row_am = $res_am->fetch_assoc();
                $cformapag = $row_am['AM_DESCRICAO'];
                $lcredito = $row_am['AM_CREDITO'];
            }
            
            $obs1 = $row_b5['B5_OBS1'];
            $obs2 = $row_b5['B5_OBS2'];
            
            if(!empty($obs1)) {
                $cobservaciones .= "\n" . $obs1;
            }

            if(!empty($obs2)) {
                $cobservaciones .= "\n" . $obs2;
            }

            $lcontinua = true;

        } else {

            $lcontinua = false;
        
        }


    }

    if($lcontinua) {

        if($lcredito==='N') {
            
            $data = array(
                "operacion"							=> "generar_comprobante",
                "tipo_de_comprobante"               => $ctpdoc,
                "serie"                             => $cSerie2,
                "numero"							=> $cnf,
                "sunat_transaction"					=> $sunat_transaction,
                "cliente_tipo_de_documento"			=> $cTipoDoc,
                "cliente_numero_de_documento"		=> $cliente_numero_documento,
                "cliente_denominacion"              => $cRazaoCli,
                "cliente_direccion"                 => $cEndCli . "\n" . $cProvCli . " " . $cDistCli . " " . $cDepaCli,
                "cliente_email"                     => $cMailCli,
                "cliente_email_1"                   => $c2MailCli,
                "cliente_email_2"                   => "",
                "fecha_de_emision"                  => $dEmissao,
                "fecha_de_vencimiento"              => $dVencimento,
                "moneda"                            => $cmoeda,
                "tipo_de_cambio"                    => $ntxmoeda,
                "porcentaje_de_igv"                 => "18.00",
                "descuento_global"                  => $ndesc,
                "descuento_global"                  => $ndesc,
                "total_descuento"                   => "",
                "total_anticipo"                    => "",
                "total_gravada"                     => $nbase,
                "total_inafecta"                    => $ninafecta,
                "total_exonerada"                   => "",
                "total_igv"                         => $nvaligv,
                "total_gratuita"                    => "",
                "total_otros_cargos"                => "",
                "total"                             => $ntotal,
                "percepcion_tipo"                   => "",
                "percepcion_base_imponible"         => "",
                "total_percepcion"                  => "",
                "total_incluido_percepcion"         => "",
                "detraccion"                        => $cdetraccion,
                "observaciones"                     => $cobservaciones,
                "documento_que_se_modifica_tipo"    => "",
                "documento_que_se_modifica_serie"   => "",
                "documento_que_se_modifica_numero"  => "",
                "tipo_de_nota_de_credito"           => "",
                "tipo_de_nota_de_debito"            => "",
                "enviar_automaticamente_a_la_sunat" => "true",
                "enviar_automaticamente_al_cliente" => $lemail_pse,
                "codigo_unico"                      => "",
                "condiciones_de_pago"               => $cformapag,
                "medio_de_pago"                     => "",
                "placa_vehiculo"                    => "",
                "orden_compra_servicio"             => "",
                "detraccion_tipo"					=> $cdetraccion_tipo,
                "detraccion_total"					=> $row_b5['B5_VALIMP3'],
                "detraccion_porcentaje"				=> $nperc_detraccion,
                "medio_de_pago_detraccion"			=> $cdetraccion_medio_de_pago,
                "tabla_personalizada_codigo"        => "",
                "formato_de_pdf"                    => "",
                "items"								=> array()
            );
            
        } else {
            
            $cmedio_pago = "CREDITO - ";
            
            $csql  = "SELECT C3_SEQ AS SEQ,";
            $csql .= " C3_VCTREAL AS VCTREAL,";
            $csql .= " C3_SALDO AS SALDO";
            $csql .= " FROM pc3990";
            $csql .= " WHERE C3_FILIAL='" . $filial . "'";
            $csql .= "   AND C3_NFISCAL='" . $cnf . "'";
            $csql .= "   AND C3_PREFIXO='" . $cSerie2 . "'";
            $csql .= "   AND C3_CLIENTE='" . $ccodecli . "'";
            $csql .= "   AND C3_SITUACAO<>'9'";
            $csql .= "   AND C3_TIPO='NF'";
            $csql .= " ORDER BY C3_SEQ";
            
            if ( $res_c3 = $conn->query($csql) ) {

                while( $row_c3 = $res_c3->fetch_assoc() ) {
                
                    $cmedio_pago .= "CUOTA " . trim(strval($row_c3['SEQ'])) . ": ";
                    $cmedio_pago .= "FECHA DE PAGO: " . substr($row_c3['VCTREAL'],6,2)."-".substr($row_c3['VCTREAL'],4,2)."-".substr($row_c3['VCTREAL'],0,4) . " - ";
                    $cmedio_pago .= "IMPORTE: " . str_replace(",","",number_format( ( $row_c3['SALDO'] ),2));
                    $cmedio_pago .= " / ";
                    
                }
                
                $cmedio_pago = substr($cmedio_pago,0,strlen(trim($cmedio_pago))-1);
                
            }
            
            
            $data = array(
                "operacion"							=> "generar_comprobante",
                "tipo_de_comprobante"               => $ctpdoc,
                "serie"                             => $cSerie2,
                "numero"							=> $cnf,
                "sunat_transaction"					=> $sunat_transaction,
                "cliente_tipo_de_documento"			=> $cTipoDoc,
                "cliente_numero_de_documento"		=> $cliente_numero_documento,
                "cliente_denominacion"              => $cRazaoCli,
                "cliente_direccion"                 => $cEndCli . "\n" . $cProvCli . " " . $cDistCli . " " . $cDepaCli,
                "cliente_email"                     => $cMailCli,
                "cliente_email_1"                   => $c2MailCli,
                "cliente_email_2"                   => "",
                "fecha_de_emision"                  => $dEmissao,
                "fecha_de_vencimiento"              => $dVencimento,
                "moneda"                            => $cmoeda,
                "tipo_de_cambio"                    => $ntxmoeda,
                "porcentaje_de_igv"                 => "18.00",
                "descuento_global"                  => $ndesc,
                "descuento_global"                  => $ndesc,
                "total_descuento"                   => "",
                "total_anticipo"                    => "",
                "total_gravada"                     => $nbase,
                "total_inafecta"                    => $ninafecta,
                "total_exonerada"                   => "",
                "total_igv"                         => $nvaligv,
                "total_gratuita"                    => "",
                "total_otros_cargos"                => "",
                "total"                             => $ntotal,
                "percepcion_tipo"                   => "",
                "percepcion_base_imponible"         => "",
                "total_percepcion"                  => "",
                "total_incluido_percepcion"         => "",
                "detraccion"                        => $cdetraccion,
                "observaciones"                     => $cobservaciones,
                "documento_que_se_modifica_tipo"    => "",
                "documento_que_se_modifica_serie"   => "",
                "documento_que_se_modifica_numero"  => "",
                "tipo_de_nota_de_credito"           => "",
                "tipo_de_nota_de_debito"            => "",
                "enviar_automaticamente_a_la_sunat" => "true",
                "enviar_automaticamente_al_cliente" => $lemail_pse,
                "codigo_unico"                      => "",
                "condiciones_de_pago"               => $cformapag,
                "medio_de_pago"                     => $cmedio_pago,
                "placa_vehiculo"                    => "",
                "orden_compra_servicio"             => "",
                "detraccion_tipo"					=> $cdetraccion_tipo,
                "detraccion_total"					=> $row_b5['B5_VALIMP3'],
                "detraccion_porcentaje"				=> $nperc_detraccion,
                "medio_de_pago_detraccion"			=> $cdetraccion_medio_de_pago,
                "tabla_personalizada_codigo"        => "",
                "formato_de_pdf"                    => "",
                "items"								=> array(),
                "venta_al_credito"					=> array()
            );
                
        }
        

        // -----------------------------------------------//
        // Generar el Detalle de las facturas             //
        // -----------------------------------------------//

        $csql = "SELECT B7_CODIGO,B7_SEQ,B7_PRODUTO,B7_QTDE,B7_PRECO,B7_DESCONTO,";
        $csql = $csql . "B7_TOTAL,B7_TES,B7_LOCAL,B7_BASIMP2,B7_VALIMP2,";
        $csql = $csql . "B7_ALQIMP2,A4_DESCRICAO,A4_UNIDADE,B7_ICMS,B7_BASEICMS,B7_ALIQICMS,";
        $csql = $csql . "B7_LOTE,B7_VALOTE";
        $csql = $csql . "  FROM pb7990,pa4990";
        $csql = $csql . " WHERE B7_SITUACAO<>'9'";
        $csql = $csql . "   AND B7_FILIAL='" . $filial . "'";
        $csql = $csql . "   AND A4_FILIAL='" . $filial . "'";
        $csql = $csql . "   AND B7_CODIGO='" . $documento . "'";
        $csql = $csql . "   AND B7_PRODUTO=A4_CODIGO";
        $csql = $csql . " ORDER BY B7_SEQ";

        if ( $res_b7 = $conn->query($csql) ) {

            while( $row_b7 = $res_b7->fetch_assoc() ) {

                $cdescripcion_produto = trim($row_b7['A4_DESCRICAO']);
                
                if(!empty(trim($row_b7['B7_LOTE']))) {
                    $cdescripcion_produto = $cdescripcion_produto . "\n - NRO LOTE: " . trim($row_b7['B7_LOTE']);
                }

                if(!empty(trim($row_b7['B7_VALOTE']))) {
                    $dvalidad_lote = substr($row_b7['B7_VALOTE'],6,2)."/".substr($row_b7['B7_VALOTE'],4,2)."/".substr($row_b7['B7_VALOTE'],0,4);
                    $cdescripcion_produto = $cdescripcion_produto . " - FECHA VAL: " . $dvalidad_lote;
                }

                $cqry = "SELECT A9_UMSUNAT";
                $cqry = $cqry . "  FROM pa9990 ";
                $cqry = $cqry . " WHERE A9_FILIAL='" . $filial . "'";
                $cqry = $cqry . "   AND A9_CODIGO='" . $row_b7['A4_UNIDADE'] . "'";

                if ( $res_a9 = $conn->query($cqry) ) {
                    $row_a9 = $res_a9->fetch_assoc(); 
                    $cumsunat = $row_a9['A9_UMSUNAT'];
                } else {
                    $cumsunat = "NIU";
                }

                if($row_b7['B7_BASIMP2']>0) {
                    $nvalunit = str_replace(",","",number_format( ( $row_b7['B7_PRECO'] / 1.18 ),2));
                    $nprcunit = str_replace(",","",number_format($row_b7['B7_PRECO'],2));
                } else { 
                    $nvalunit = str_replace(",","",number_format($row_b7['B7_PRECO'],2));
                    $nprcunit = str_replace(",","",number_format( ( $row_b7['B7_PRECO']+($row_b7['B7_ICMS']/$row_b7['B7_QTDE'])),2));
                }

                if($row_b7['B7_BASEICMS']>0) {
                    $nsubtotal = str_replace(",","",number_format($row_b7['B7_BASEICMS'],2));
                    $nigv = str_replace(",","",number_format($row_b7['B7_ICMS'],2));
                    $ntotal = str_replace(",","",number_format( ( $row_b7['B7_TOTAL']+$row_b7['B7_ICMS'] ),2));
                } else { 
                    $nsubtotal = str_replace(",","",number_format($row_b7['B7_BASIMP2'],2));
                    $nigv = str_replace(",","",number_format($row_b7['B7_VALIMP2'],2));
                    $ntotal = str_replace(",","",number_format($row_b7['B7_TOTAL'],2));
                }
                
                if($ninafecta>0) {
                    $nsubtotal = str_replace(",","",number_format($ninafecta,2));
                }
                
                $data["items"][] = array(
                    "unidad_de_medida"				=> $cumsunat,
                    "codigo"						=> trim($row_b7['B7_PRODUTO']),
                    "descripcion"					=> $cdescripcion_produto,				//trim($ors2->fields[12]),
                    "cantidad"						=> trim($row_b7['B7_QTDE']),
                    "valor_unitario"				=> $nvalunit,							//trim($ors->fields[4]/1.18),
                    "precio_unitario"				=> $nprcunit,							//trim($ors->fields[4]),
                    "descuento"						=> "",									//$ndesc,
                    "subtotal"						=> $nsubtotal,							//trim($ors->fields[9]),
                    "tipo_de_igv"					=> $ctpdeigv,
                    "igv"							=> $nigv,								//trim($ors->fields[10]),
                    "total"							=> $ntotal,
                    "anticipo_regularizacion"		=> "false",
                    "anticipo_documento_serie"		=> "",
                    "anticipo_documento_numero"		=> ""
                );

            }
                            
        }

        if($lcredito==='S') {
            
            $csql  = "SELECT C3_SEQ AS SEQ,";
            $csql .= " C3_VCTREAL AS VCTREAL,";
            $csql .= " C3_SALDO AS SALDO";
            $csql .= " FROM pc3990";
            $csql .= " WHERE C3_FILIAL='" . $filial . "'";
            $csql .= "   AND C3_NFISCAL='" . $cnf . "'";
            $csql .= "   AND C3_PREFIXO='" . $cSerie2 . "'";
            $csql .= "   AND C3_CLIENTE='" . $ccodecli . "'";
            $csql .= "   AND C3_SITUACAO<>'9'";
            $csql .= "   AND C3_TIPO='NF'";
            $csql .= " ORDER BY C3_SEQ";
            
            if ( $res_c3 = $conn->query($csql) ) {

                while( $row_c3 = $res_c3->fetch_assoc() ) {

                    $data["venta_al_credito"][]	= array(
                        "cuota" 			=> trim(strval($row_c3['SEQ'])),
                        "fecha_de_pago"		=> substr($row_c3['VCTREAL'],6,2)."-".substr($row_c3['VCTREAL'],4,2)."-".substr($row_c3['VCTREAL'],0,4),
                        "importe"			=> str_replace(",","",number_format( ( $row_c3['SALDO'] ),2))
                    );
                    
                }
                
            }
            
        }

        $cArqlog = $fecha_emision . "-" . trim($c_a0_cgc) . "-" . $tpdoc1 . "-" . $cSerie2 . "-" . $cnf . ".txt";

        $cArqJson = $fecha_emision . "-" . trim($c_a0_cgc) . "-" . $tpdoc1 . "-" . $cSerie2 . "-" . $cnf . ".json";
        $cNomeArq = $ccaminho . $fecha_emision . "-" . trim($c_a0_cgc) . "-" . $tpdoc1 . "-" . $cSerie2 . "-" . $cnf . ".json";

        $jsonencoded = json_encode($data,JSON_UNESCAPED_UNICODE);
            
        $fh = fopen($cNomeArq, 'w');
        fwrite($fh, $jsonencoded);
        fclose($fh);

        // RUTA para enviar documentos
        //$ruta = "https://api.nubefact.com/api/v1/186dd3ef-42a0-4669-9897-5ab8b65033e2";
        $ruta = "";

        //TOKEN para enviar documentos
        //$token = "19610a168b7c4ca8ac0917feef6c4ba41d72b53b8409470796ebfdd08b066a21";
        $token = "";

        $cqry = "SELECT ZH_CONTEUDO";
        $cqry = $cqry . "  FROM pzh990 ";
        $cqry = $cqry . " WHERE ZH_FILIAL='" . $filial . "'";
        $cqry = $cqry . "   AND ZH_CODIGO='P_NFENLAC'";

        if ( $res_zh = $conn->query($cqry) ) {
            $row_zh = $res_zh->fetch_assoc();
            $ruta = trim($row_zh['ZH_CONTEUDO']);
        }

        $cqry = "SELECT ZH_CONTEUDO";
        $cqry = $cqry . "  FROM pzh990 ";
        $cqry = $cqry . " WHERE ZH_FILIAL='" . $filial . "'";
        $cqry = $cqry . "   AND ZH_CODIGO='P_NFTOKEN'";

        if ( $res_zh = $conn->query($cqry) ) {
            $row_zh = $res_zh->fetch_assoc();
            $token = trim($row_zh['ZH_CONTEUDO']);
        }

        //$ruta = "https://api.nubefact.com/api/v1/186dd3ef-42a0-4669-9897-5ab8b65033e2";
        //$token = "19610a168b7c4ca8ac0917feef6c4ba41d72b53b8409470796ebfdd08b066a21";


        if(!empty($ruta) && !empty($token)) {
        
        
            //Invocamos el servicio de NUBEFACT
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $ruta);
            curl_setopt(
                $ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Token token="'.$token.'"',
                'Content-Type: application/json',
                )
            );
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$jsonencoded);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $respuesta  = curl_exec($ch);
            curl_close($ch);

            $leer_respuesta = json_decode($respuesta, true);
            if (isset($leer_respuesta['errors'])) {
                
                $clog = $leer_respuesta['errors'];
                $clog = str_replace("'","",$clog);

                $xsql = "UPDATE pb5990 SET ";
                $xsql = $xsql . "  B5_ARQLOG='" . $clog . "'";
                $xsql = $xsql . " WHERE B5_CODIGO='" . $documento . "'";
                $xsql = $xsql . "   AND B5_FILIAL='" . $filial . "'";
        
                if ($conn->query($xsql) === TRUE) {
        
                    $item = array(
                        "estado" => "404",
                        "msg" => "¡documento NO transmitido!",
                        "log" => $clog,
                    );
        
                } else {
        
                    $item = array(
                        "estado" => "404",
                        "msg" => "¡error en la actualizacion del LOG de la factura 1!",
                    );
                }
        
            } else {

                $cod_hash = $leer_respuesta['codigo_hash'];

                $clog  = "<table border=1>";
                $clog .= "<tbody>";
                $clog .= "<tr><th>serie:</th><td>" . $leer_respuesta['serie'] . "</td></tr>";
                $clog .= "<tr><th>numero:</th><td>" . $leer_respuesta['numero'] . "</td></tr>";
                $clog .= "<tr><th>Mensaje SUNAT:</th><td>" . $leer_respuesta['sunat_description'] . "</td></tr>";
                $clog .= "<tr><th>sunat_note:</th><td>" . $leer_respuesta['sunat_note'] . "</td></tr>";
                $clog .= "</tbody>";
                $clog .= "</table>";
                
                $clog = str_replace("'","",$clog);

                $xsql = "UPDATE pb5990 SET ";
                $xsql = $xsql . "  B5_ARQFAC='" . $cArqJson . "',";
                $xsql = $xsql . "  B5_ARQLOG='" . $clog . "',";
                $xsql = $xsql . "  B5_NRONFE='" . $leer_respuesta['cadena_para_codigo_qr'] . "',";
                $xsql = $xsql . "  B5_NFEPROT='" . $leer_respuesta['codigo_hash'] . "',";
                $xsql = $xsql . "  B5_ENLACEFE='" . $leer_respuesta['enlace'] . "',";
                $xsql = $xsql . "  B5_SITUACAO='1'";
                $xsql = $xsql . " WHERE B5_CODIGO='" . $documento . "'";
                $xsql = $xsql . "   AND B5_FILIAL='" . $filial . "'";

                if ($conn->query($xsql) === TRUE) {

                    $item = array(
                            "estado" => "200",
                            "msg" => "¡documento transmitido con exito!",
                            "log" => $clog,
                    );
            
                } else {
            
                    $item = array(
                        "estado" => "404",
                        "msg" => "¡error en la actualizacion del LOG de la factura 2!",
                    );
                }

            }
            
        } else {

            $item = array(
                "estado" => "404",
                "msg" => "¡Empresa NO esta configurada para envio de documentos electronicos!",
            );
            
        }	// nao encontro la ruta ni el token

    
    } else {

        $item = array(
            "estado" => "404",
            "msg" => "¡No se encontro el documemto seleccionado!",
        );
    
    }

} else {

    $item = array(
        "estado" => "404",
        "msg" => "¡Nro del documemto en blanco!",
    );
    
}

$conn->close();

array_push($ret_nfe, $item);
echo json_encode($ret_nfe);    

?>