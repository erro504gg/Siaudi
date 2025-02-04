<?php
/********************************************************************************
*  Copyright 2015 Conab - Companhia Nacional de Abastecimento                   *
*                                                                               *
*  Este arquivo é parte do Sistema SIAUDI.                                      *
*                                                                               *
*  SIAUDI  é um software livre; você pode redistribui-lo e/ou                   *
*  modificá-lo sob os termos da Licença Pública Geral GNU conforme              *
*  publicada pela Free Software Foundation; tanto a versão 2 da                 *
*  Licença, como (a seu critério) qualquer versão posterior.                    *
*                                                                               *
*  SIAUDI é distribuído na expectativa de que seja útil,                        *
*  porém, SEM NENHUMA GARANTIA; nem mesmo a garantia implícita                  *
*  de COMERCIABILIDADE OU ADEQUAÇÃO A UMA FINALIDADE ESPECÍFICA.                *
*  Consulte a Licença Pública Geral do GNU para mais detalhes em português:     *
*  http://creativecommons.org/licenses/GPL/2.0/legalcode.pt                     *
*                                                                               *
*  Você deve ter recebido uma cópia da Licença Pública Geral do GNU             *
*  junto com este programa; se não, escreva para a Free Software                *
*  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA    *
*                                                                               *
*  Sistema   : SIAUDI - Sistema de Auditoria Interna                            *
*  Data      : 05/2015                                                          *
*                                                                               *
********************************************************************************/
?>
<?php echo $this->renderPartial('/layouts/_dialogo_view'); ?>

<?php 
// carrega tela de consulta conforme perfil do usuário 
//$arquivo = "_search_auditor";
$perfil = strtolower(Yii::app()->user->role);
$perfil = str_replace("siaudi2","siaudi",$perfil);
$arquivo = "_search_cliente_item"; // arquivo padrão; 
if ($perfil=="siaudi_cliente") { $arquivo = "_search_cliente"; }
if ($perfil=="siaudi_auditor") {$arquivo = "_search_auditor"; }
if ($perfil=="siaudi_gerente" || $perfil=="siaudi_chefe_auditoria" || $perfil == 'siaudi_gerente_nucleo') { $arquivo = "_search_gerente"; }


 
$this->renderPartial($arquivo, array('model' => $model,));
?>
