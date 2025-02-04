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
<?php

Yii::import('application.models.table._base.BaseRelatorio');

class Relatorio extends BaseRelatorio {

	public $unidade_administrativa_fk, $diretoria_fk, $gerente_fk, $tipo_relatorio,
	$dias_uteis, $ano, $prazo_manifestacao, $relatorio_riscopos,
	$periodo_inicio, $periodo_fim, $filtro_acesso, $sureg_secundaria;

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		$attribute_default = parent::attributeLabels();

		$attribute_custom = array(
            'id' => Yii::t('app', 'ID do Relatório'),
            'relatorio_fk' => Yii::t('app', 'Nº Relatório'),
            'numero_relatorio' => Yii::t('app', 'Número'),
            'data_relatorio' => Yii::t('app', 'Data de Homologação'),
            'especie_auditoria_fk' => null,
            'descricao_introducao' => Yii::t('app', 'Mensagem de Introdução'),
            'categoria_fk' => null,
            'data_pre_finalizado' => Yii::t('app', 'Data de Pré-Finalização'),
            'data_finalizado' => Yii::t('app', 'Data de Finalização'),
            'valor_prazo' => Yii::t('app', 'Prazo'),
            'st_libera_homologa' => Yii::t('app', 'Libera Homologação'),
            'data_gravacao' => Yii::t('app', 'Data de Gravação'),
            'data_regulariza' => Yii::t('app', 'Data de Regularização'),
            'login_relatorio' => Yii::t('app', 'Login Criação'),
            'login_finaliza' => Yii::t('app', 'Login Finalização'),
            'login_pre_finaliza' => Yii::t('app', 'Login Pré-Finaliza'),
            'login_homologa' => Yii::t('app', 'Login Homologação'),
            'diretoria_fk' => Yii::t('app', 'Diretoria/Presidência'),
            'unidade_administrativa_fk' => Yii::t('app', 'Unidade Auditada'),
            'auditor_fk' => Yii::t('app', 'Do(s) Auditor(es)'),
            'gerente_fk' => Yii::t('app', 'Ao Gerente de Auditoria'),
            'tipo_relatorio' => Yii::t('app', 'Tipo de relatório'),
            'ano' => Yii::t('app', 'Ano'),
            'prazo_manifestacao' => Yii::t('app', 'Prazo para manifestação'),
            'relatorio_riscopos' => Yii::t('app', 'Riscos Pós Identificados'),
            'nucleo' => Yii::t('app', 'Emitido pelo Núcleo'),
            'area' => Yii::t('app', 'Área(s)'),
            'setor' => Yii::t('app', 'Setor(es)'),
            'plan_especifico_fk' =>Yii::t('app', 'Planejamento Específico'),
            'sureg_secundaria' => Yii::t('app', 'Unidade Secundária (relacionada)'),
            'planEspecificoFk' => null,
            'especieAuditoriaFk' => null,
            'categoriaFk' => null,
				'objetoFk' =>Yii::t('app', 'Objeto'),
		);
		return array_merge($attribute_default, $attribute_custom);
	}

	public function afterFind() {
		parent::afterFind();
		if (isset($this->data_relatorio))
		$this->data_relatorio = MyFormatter::converteData($this->data_relatorio);
		if (isset($this->data_pre_finalizado))
		$this->data_pre_finalizado = MyFormatter::converteData($this->data_pre_finalizado);
		if (isset($this->data_finalizado))
		$this->data_finalizado = MyFormatter::converteData($this->data_finalizado);
		if (isset($this->data_gravacao))
		$this->data_gravacao = MyFormatter::converteData($this->data_gravacao);
		if (isset($this->data_regulariza))
		$this->data_regulariza = MyFormatter::converteData($this->data_regulariza);
		if (isset($this->nucleo) && ($this->nucleo == ''))
		$this->nucleo = 0;
	}

	// pega todos os relatórios discriminados (ou um relatório específico),
	// por espécie de auditoria para carregar a combo
	// dos capítulos, itens e recomendações.
	// A variável parâmetros serve para filtrar relatórios não finalizados, exemplo data_finalizado=null
        // @homologados (boolean) - se verdadeiro, então apresenta a label com nº do relatório e data
	public function relatorio_por_especie($id_relatorio = null, $parametros = null, $homologados=null) {
		$schema = Yii::app()->params['schema'];
		$sql_id_relatorio = ($id_relatorio) ? " WHERE t.id=" . $id_relatorio : null;
		$sql_parametros = ($parametros) ? " WHERE t." . $parametros : null;
		$sql = "SELECT t.id, t.numero_relatorio, t.data_relatorio, e.sigla_auditoria, e.nome_auditoria
                        FROM " . $schema . ".tb_relatorio t LEFT JOIN  " . $schema . ".tb_especie_auditoria e
                        ON t.especie_auditoria_fk = e.id {$sql_id_relatorio} {$sql_parametros} ORDER BY t.data_relatorio DESC, t.id ASC";

		$command = Yii::app()->db->createCommand($sql);
		$result = $command->query();
		$relatorios = $result->readAll();

		if ($id_relatorio) {
			return $relatorios[0]->nome_auditoria;
		}

		if (is_array($relatorios)) {
			if (sizeof($relatorios)){
				foreach ($relatorios as $vetor) {
                                        if($homologados){
					$vetor_relatorios[] = array("id" => $vetor['id'],
                                                                    "numero_relatorio" => $vetor['numero_relatorio'] . " de " . MyFormatter::converteData($vetor['data_relatorio']));
                                        }else {
					$vetor_relatorios[] = array("id" => $vetor['id'],
                                                                    "sigla_auditoria" => $vetor['id'] . " - " . $vetor['sigla_auditoria']);
                                        }
				}
			}
		}

		$vetor_completo = $vetor_relatorios;

		if (is_array($vetor_completo) != true) {
			$vetor_completo = array(null);
		}
		return ($vetor_completo);
	}

	// Faz a mesma coisa do método relatorio_por_especie, porém,
	//  selecione somente os não homologados para a combo dos cadastros
	// de relatório, capítulos e itens
	public function relatorio_por_especie_combo($id_relatorio = null, $parametros = null) {
		$schema = Yii::app()->params['schema'];
		$sql_id_relatorio = ($id_relatorio) ? " AND t.id=" . $id_relatorio : null;
		$sql_parametros = ($parametros) ? " AND t." . $parametros : null;
		$sql = "SELECT t.id, e.sigla_auditoria, e.nome_auditoria
                        FROM " . $schema . ".tb_relatorio t LEFT JOIN  " . $schema . ".tb_especie_auditoria e
                        ON t.especie_auditoria_fk = e.id WHERE data_relatorio is null {$sql_id_relatorio} {$sql_parametros} ORDER BY t.id DESC";
		$command = Yii::app()->db->createCommand($sql);
		$result = $command->query();
		$relatorios = $result->readAll();

		if ($id_relatorio) {
			return $relatorios[0]->nome_auditoria;
		}

		if (is_array($relatorios)) {
			if (sizeof($relatorios)){
				foreach ($relatorios as $vetor) {
					$vetor_relatorios[] = array("id" => $vetor['id'],
	                    "sigla_auditoria" => $vetor['id'] . " - " . $vetor['sigla_auditoria']);
				}
			}
		}
		$vetor_completo = $vetor_relatorios;

		if (is_array($vetor_completo) != true) {
			$vetor_completo = array(null);
		}
		return ($vetor_completo);
	}

	/* Finalização do relatório feita em 2 etapas:
	 * -------------
	 * 1 - Liberar acesso dos auditados a este relatório
	 * 2 - Pegar e-mail de todos envolvidos (auditores, auditados, suregs e gerência) e enviar e-mail
	 * -------------
	 * @id (int): id do relatório
	 * @manifestacao (int): caso seja 1, então libera para manifestação.
	 *                      caso seja 0, então bloqueia manifestação e vai direto para homologação.
	 *                      Neste método, a variável $manifestacao também repassa o seu valor
	 *                      para o método que irá enviar o e-mail, pois o sistema somente envia
	 *                      e-mail sobre a manifestação, caso haja o prazo de 5 dias úteis (na
	 *                      finalização sem prazo, o sistema não envia e-mail).
	 */

	public function finalizar_relatorio($id, $manifestacao) {
		$schema = Yii::app()->params['schema'];
		// -----------------------------------------------------
		// passo 1 - liberar acesso dos auditados ao relatório
		// -----------------------------------------------------
		// Pega siaudi_cliente das Unidades Regionais do relatório
                
		$relatorioUnidadeAdm = RelatorioSureg::model()->findAllByAttributes(array('relatorio_fk' => $id));
                //PORTAL_SPB identificar quem é o responsável pela unidade: é aquele que tiver perfil siaudi_cliente (151)
		if (sizeof($relatorioUnidadeAdm)){
			foreach ($relatorioUnidadeAdm as $vetor) {
				$UnidadeAdministrativa = UnidadeAdministrativa::model()->findbyAttributes(array('id' => $vetor->unidade_administrativa_fk));
				// Pesquisa quem é o responsável pela unidade auditada
					$sql_cliente = "SELECT vusuario.nome_login, vusuario.substituto_fk, vusuario.email
	                                    FROM " . $schema . ".tb_usuario vusuario 
	                                    WHERE (vusuario.unidade_administrativa_fk = '" . $vetor->unidade_administrativa_fk . "')
	                                    AND (vusuario.perfil_fk = (SELECT id FROM " . $schema . ".tb_perfil WHERE nome_interno = 'SIAUDI_CLIENTE'))";
                                       
					$command = Yii::app()->db->createCommand($sql_cliente);
					$result = $command->query();
					$resultado = $result->readAll();
                                        //PORTAL_SPB alterada a forma de gerar e-mail
					if (sizeof($resultado)){
						foreach ($resultado as $vetor_cliente) {
							$login = $vetor_cliente[nome_login];
                                                        $email = $vetor_cliente[email];
							$relatorio_acesso1 = RelatorioAcesso::model()->inserir($id, $login, $vetor->unidade_administrativa_fk);
							$vetor_email[] = $email;
							// verifica se o usuário está cadastrado no corporativo
							//$relatorio_acesso2 = RelatorioAcesso::model()->VerificaAuditadoCorporativo($login);
							//busca substituto
							if ($vetor_cliente[substituto_fk]) {
								$login_substituto = Usuario::model()->findByPk($vetor_cliente[substituto_fk]);
								$relatorio_acesso1 = RelatorioAcesso::model()->inserir($id, $login_substituto->nome_login, $vetor->unidade_administrativa_fk);
								$vetor_email[] = $login_substituto->email;
								//$relatorio_acesso2 = RelatorioAcesso::model()->VerificaAuditadoCorporativo($login_substituto->nome_login);
							}
						}
					}
	
			}
		}

		// ---------------------------------------------
		// passo 2 - Pegar e-mail dos envolvidos
		// ---------------------------------------------
		// pega os auditores do relatório
		$relatorio_auditor = RelatorioAuditor::model()->findAllByAttributes(array('relatorio_fk' => $id));
		if (sizeof($relatorio_auditor)){
			foreach ($relatorio_auditor as $vetor) {
				$auditor = Usuario::model()->findByAttributes(array('id' => $vetor->usuario_fk));
				$vetor_email[] = $auditor->email;
			}
		}

		// pega os gerentes do relatório
		$relatorio_gerente = RelatorioGerente::model()->findAllByAttributes(array('relatorio_fk' => $id));
		if (sizeof($relatorio_gerente)){
			foreach ($relatorio_gerente as $vetor) {
				$gerente = usuario::model()->findByAttributes(array('id' => $vetor->usuario_fk));
				$vetor_email[] = $gerente->email;
			}
		}
                

		// Se manifestação for autorizada, então pega e-mails das
		//  unidades administrativas (auditados) do relatório
//		$relatorioUnidadeAdm = RelatorioSureg::model()->findAllByAttributes(array('relatorio_fk' => $id));
//		if ($manifestacao == 1) {
//			if (sizeof($relatorioUnidadeAdm)){
//				foreach ($relatorioUnidadeAdm as $vetor) {
//					$unidade_administrativa = UnidadeAdministrativa::model()->findByAttributes(array('id' => $vetor->unidade_administrativa_fk));
//					// limpa caracterre º
//					$unidade_sigla = str_replace("º", "", $unidade_administrativa->sigla);
//					// considere somente valores até a barra. Exemplo: CPL / AM => CPL
//					$barra = strpos($unidade_sigla, "/");
//					if ($barra) {
//						$unidade_sigla = substr($unidade_sigla, 0, $barra);
//					}
//					$unidade_sigla = trim($unidade_sigla);
//                			$vetor_email[] = strtolower($unidade_sigla); // no final, vai ficar sigla"@dominio";
//				}
//			}
//		}
		$this->finalizar_relatorio_email($id, $vetor_email, $manifestacao);
}

/* Envia e-mails, após finalização do relatório.
 * @id_relatorio (int): ID do relatório
 * @e-mails (array): logins para enviar os e-mails
 * @manifestacao (int): Se 1 então envia e-mail informando que pode se manifestar.
 *                      Se 0, então não envia e-mails.
 */

public function finalizar_relatorio_email($id_relatorio, $emails, $manifestacao=null) {
	// pega o título do relatório
	$model_relatorio = Relatorio::model()->findByAttributes(array('id' => $id_relatorio));
	$especie_auditoria = EspecieAuditoria::model()->findByAttributes(array('id' => $model_relatorio->especie_auditoria_fk));
	$titulo_relatorio = $id_relatorio . " - " . $especie_auditoria->sigla_auditoria;
	// configura parâmetros para enviar o e-mail
	$headers = "Reply-To: SIAUDI <".Yii::app()->params['adminEmail'].">\r\n";
	$headers .= "Return-Path: SIAUDI<".Yii::app()->params['adminEmail'].">\r\n";
	$headers .= "From: Auditoria <".Yii::app()->params['adminEmail'].">\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html;charset=iso-8859-1\r\n";
	$assunto = 'SIAUDI - Relatório de Auditoria Finalizado';

	// formata texto html
        //PORTAL_SPB
	$mensagem = "<html><head></head><body><font face='Verdana' size='2'>";
	$mensagem .= "Consoante ao Manual de Auditoria Interna, disponibilizamos a versão preliminar do <strong>Relatório de Auditoria Id Relatório: " . $titulo_relatorio . "</strong>, para apreciação do responsável pela unidade auditada, <strong>no prazo improrrogável de 05(cinco) dias úteis</strong>.<br><br>";
	$mensagem .= "Este procedimento ajuda a assegurar de que não se verificam mal entendidos ou  incompreensões acerca dos fatos relatados.<br><br>";
	$mensagem .= "Após a homologação do Relatório, abrir-se-á prazo de 20 (vinte) dias úteis para que se promovam as devidas respostas às eventuais recomendações de auditoria.<br><br>";
	$mensagem .= "Acesse o Relatório de Auditoria via SIAUDI.";
	$mensagem .= "</font></body></html>";

	// Somente envia e-mails às partes envolvidas,
	// caso haja prazo para manifestação. Havendo finalização
	// sem prazo, o sistema não envia e-mails.
        //PORTAL_SPB alterada a forma de gerar e-mail
	if ($manifestacao) {
		// envia e-mails
		if (sizeof($emails)){
			foreach ($emails as $vetor) {
				// verifica se o e-mail para este relatório já foi enviado
				// para evitar mais de 1 envio do mesmo relatório para o mesmo
				// destinarário
				if (!$check_email[$vetor]) {
					$check_email[$vetor] = 1;
					$destinatario = $vetor;// . Yii::app()->params['dominioEmail'];
					$ok = $this->Envia_email($destinatario, $assunto, $mensagem, $headers);
				}
			}
		}
	}
}

/* Pré-Finalização do relatório:
 * -------------
 * 1 - Pegar e-mail do gerente envolvido e enviar e-mail
 * -------------
 * @id (int): id do relatório
 */

public function pre_finalizar_relatorio($id) {
	// pega os gerentes do relatório
	$relatorio_gerente = RelatorioGerente::model()->findAllByAttributes(array('relatorio_fk' => $id));
        //PORTAL_SPB alterada a forma de gerar e-mail
	if (sizeof($relatorio_gerente)){
		foreach ($relatorio_gerente as $vetor) {
			$gerente = Usuario::model()->findByAttributes(array('id' => $vetor->usuario_fk));
			$vetor_email[] = $gerente->email;
		}
	}
	// pega o e-mail do chefe de auditoria
        //PORTAL_SPB obter o usuário que é chefe de auditoria
	$chefe_auditoria = Usuario::model()->findAllByAttributes(array(), $condition = "perfil_fk = :perfil_fk", $param = array(':perfil_fk' => 150));

	//adicionando o chefe de auditoria na lista de e-mails
	//$vetor_email[] = $chefe_auditoria[0]['nome_login'];

	//retirando os e-mails duplicados, caso exista
	$vetor_email = array_unique($vetor_email);

	$this->pre_finalizar_relatorio_email($id, $vetor_email);
}

/* Envia e-mails, após pré-finalização do relatório gerado pelo núcleo.
 * @id_relatorio (int): ID do relatório
 * @e-mails (array): logins para enviar os e-mails
 */

public function pre_finalizar_relatorio_email($id_relatorio, $emails) {

	// pega o título do relatório
	$model_relatorio = Relatorio::model()->findByAttributes(array('id' => $id_relatorio));
	$especie_auditoria = EspecieAuditoria::model()->findByAttributes(array('id' => $model_relatorio->especie_auditoria_fk));
	$titulo_relatorio = $id_relatorio . " - " . $especie_auditoria->sigla_auditoria;
	// configura parâmetros para enviar o e-mail
	$headers = "Reply-To: SIAUDI <".Yii::app()->params['adminEmail'].">\r\n";
	$headers .= "Return-Path: SIAUDI <".Yii::app()->params['adminEmail'].">\r\n";
	$headers .= "From: Unidade de Auditoria Interna <".Yii::app()->params['adminEmail'].">\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html;charset=iso-8859-1\r\n";
	$assunto = 'SIAUDI - Relatório de Auditoria Pré-finalizado';

	// formata texto html
	$mensagem = "<html><head></head><body><font face='Verdana' size='2'>";
	$mensagem .= "O Relatório de Auditoria Id Relatório: <strong>" . $titulo_relatorio . "</strong>, foi pré-finalizado pelo núcleo de auditoria.<br><br>";
	$mensagem .= "Acesse o Relatório de Auditoria via SIAUDI.";
	$mensagem .= "</font></body></html>";

	// envia e-mails
        //PORTAL_SPB alterada a forma de gerar e-mail
	if (sizeof($emails)){
		foreach ($emails as $vetor) {
			// verifica se o e-mail para este relatório já foi enviado
			// para evitar mais de 1 envio do mesmo relatório para o mesmo
			// destinarário
			if (!$check_email[$vetor]) {
				$check_email[$vetor] = 1;
				$destinatario = $vetor;// . Yii::app()->params['dominioEmail'];
				$ok = $this->Envia_email($destinatario, $assunto, $mensagem, $headers);
			}
		}
	}
}

public function search() {
	$schema = Yii::app()->params['schema'];
	$criteria = new CDbCriteria;
	$criteria->alias = 'relatorio';
	$criteria->addCondition('data_relatorio IS NULL');

	if ($this->diretoria_fk || $this->unidade_administrativa_fk) {
		$criteria->join = "LEFT JOIN {$schema}.tb_relatorio_diretoria RD ON RD.relatorio_fk=relatorio.id
                                 LEFT JOIN {$schema}.tb_relatorio_sureg RS ON RS.relatorio_fk=relatorio.id";
		if ($this->diretoria_fk) {
			$criteria->addcondition('RD.diretoria_fk=' . $this->diretoria_fk);
		}
		if ($this->unidade_administrativa_fk) {
			$criteria->addcondition('RS.unidade_administrativa_fk=' . $this->unidade_administrativa_fk);
		}
	}
	$criteria->compare('id', $this->id);
	$criteria->compare('numero_relatorio', $this->numero_relatorio);
	$criteria->compare('especie_auditoria_fk', $this->especie_auditoria_fk);
	$criteria->compare('descricao_introducao', $this->descricao_introducao, true);
	$criteria->compare('categoria_fk', $this->categoria_fk);
	$criteria->distinct = true;
	return new CActiveDataProvider($this, array(
            'criteria' => $criteria)
	);
}

// Verifica se todos os relatórios finalizados e não liberados para homologação
// ainda estão no prazo para manifestação
// (encerra somente os relatórios onde NÃO HOUVE,
//  manifestação contrária)
public function FechaPrazoRelatorioFinalizado() {
	$Relatorios_finalizados = Relatorio::model()->findAll('data_relatorio IS NULL and st_libera_homologa IS NULL and data_finalizado IS NOT NULL');
	if (sizeof($Relatorios_finalizados)){
		foreach ($Relatorios_finalizados as $vetor) {
			// verifica se o prazo para manifestação já expirou
			$data_final = Feriado::model()->DiasUteis($vetor->data_finalizado, 5);
			$data_final = explode("/", $data_final);
			$data_final = $data_final[2] . $data_final[1] . $data_final[0];
			$hoje = date("Ymd");
	
			// verifica se houve manifestação contrária
			$Manifestacao = Manifestacao::model()->findAllByAttributes(array('relatorio_fk' => $vetor->id, 'status_manifestacao' => 0));
	
			// se não houve manifestação contrária e prazo expirou, então
			// altera st_libera_homolga da tb_relatorio
			if (sizeof($Manifestacao) == 0 && $hoje > $data_final) {
				$libera = $this->LiberaHomologa($vetor->id);
				$enviar_email = Manifestacao::model()->VerificaManifestacao($vetor->id, 'tacita');
			}
		}
	}
}

// Verifica se o auditor possui mais de 10 dias utéis sem
// se manisfestar após a reposta do auditado.
public function VerificaManifestacaoAuditor() {
	$schema = Yii::app()->params['schema'];
	$mensagem = "";
	$vetor_email = array();
	$sql = "select relatorio.id as relatorio_fk, relatorio.numero_relatorio, relatorio.data_relatorio, resposta.data_resposta,
                       resposta.descricao_resposta, recomendacao.unidade_administrativa_fk, item.numero_item
                  from " . $schema . ".tb_relatorio as relatorio
                 inner join " . $schema . ".tb_capitulo as capitulo on 
                       capitulo.relatorio_fk = relatorio.id 
                 inner join " . $schema . ".tb_item as item on 
                       item.capitulo_fk = capitulo.id
                 inner join " . $schema . ".tb_recomendacao as recomendacao on 
                       recomendacao.item_fk = item.id 
                 inner join " . $schema . ".tb_resposta as resposta on 
                       resposta.recomendacao_fk = recomendacao.id
                 inner join " . $schema . ".tb_unidade_administrativa as sureg on 
                       sureg.id = recomendacao.unidade_administrativa_fk
                 where relatorio.numero_relatorio is not null 
                   and relatorio.data_relatorio is not null 
                   and recomendacao.recomendacao_tipo_fk = (SELECT id FROM " . $schema . ".tb_recomendacao_tipo WHERE nome_tipo ilike '%recomendação%')
                   and resposta.id = (select max(id) 
                                        from " . $schema . ".tb_resposta as aux 
                                        where aux.recomendacao_fk = resposta.recomendacao_fk)
                   and not exists (select 1
                                     from " . $schema . ".tb_usuario as auditor 
                                    where auditor.nome_login = resposta.id_usuario_log)";
	$command = Yii::app()->db->createCommand($sql);
	$dados = $command->query();
	$relatorios = $dados->readAll();
	if (sizeof($relatorios)) {
		// configura parâmetros para enviar o e-mail
		$headers = "Reply-To: SIAUDI <".Yii::app()->params['adminEmail'].">\r\n";
		$headers .= "Return-Path: SIAUDI<".Yii::app()->params['adminEmail'].">\r\n";
		$headers .= "From: Unidade de Auditoria Interna <".Yii::app()->params['adminEmail'].">\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html;charset=iso-8859-1\r\n";
		$assunto = 'SIAUDI - Relatório sem Manifestação - Auditor';
		
		if (sizeof($relatorios)){
			foreach ($relatorios as $vetor) {
				$data_inicio = date("d/m/Y", strtotime(is_null($vetor['data_resposta']) ? $vetor['data_relatorio'] : $vetor['data_resposta']));
				//primeira verificação: verifica se passou 10 dias úteis
				$data_final = Feriado::model()->DiasUteis($data_inicio, 10);
				$data_final = explode("/", $data_final);
				$data_final = $data_final[2] . $data_final[1] . $data_final[0];
				$hoje = date("Ymd");
				if ($data_final == $hoje) {
					$mensagem = "<html><head></head><body><font face='Verdana' size='2'>";
					$mensagem .= "Alertamos que o auditado se manifestou, há dez dia úteis, sobre a recomendação exarada no item ".$vetor['numero_item']." do Relatório n.º ". $vetor['numero_relatorio'].".
	                                  Solicitamos a imediata avaliação do citado item";
					$mensagem .= "</font></body></html>";
				}
                                //PORTAL_SPB alterada a forma de gerar e-mail
				if ($mensagem) {
					$dados_auditor = RelatorioAuditor::model()->findAll("relatorio_fk =" . $vetor['relatorio_fk']);
					if (sizeof($dados_auditor)){
						foreach($dados_auditor as $vetor_auditor){
							$auditor = Usuario::model()->findByPk($vetor_auditor['usuario_fk']);
							$vetor_email[] = $auditor->email;
						}
					}
	
					if (sizeof($vetor_email)) {
						foreach ($vetor_email as $destinatario) {
							//$destinatario .= Yii::app()->params['dominioEmail'];
							$ok = $this->Envia_email($destinatario, $assunto, $mensagem, $headers);
						}
					}
				}
			}
		}
	}
}

// Verifica se o auditado possui mais de 20 dias utéis ou 60 dias corridos sem
// se manisfestar após a homologação do relatório.
public function VerificaManifestacaoAuditado() {
	$schema = Yii::app()->params['schema'];
	$mensagem = "";
	$sql = "select relatorio.id as relatorio_fk, relatorio.numero_relatorio, relatorio.data_relatorio, recomendacao.unidade_administrativa_fk,
                       resposta.data_resposta, resposta.descricao_resposta, item.numero_item, recomendacao.numero_recomendacao,
                       (select aux.data_resposta
                          from " . $schema . ".tb_resposta as aux
                         inner join " . $schema . ".tb_usuario as auditor on
                               auditor.nome_login = aux.id_usuario_log
                         where aux.tipo_status_fk in (SELECT id FROM " . $schema . ".tb_tipo_status WHERE descricao_status IN ('Pendente', 'Em Implementação'))
                           and aux.recomendacao_fk = resposta.recomendacao_fk
                         order by resposta.id desc
                         LIMIT 1) as data_resposta_auditor         
                    from " . $schema . ".tb_relatorio as relatorio
                   inner join " . $schema . ".tb_capitulo as capitulo on 
                         capitulo.relatorio_fk = relatorio.id 
                   inner join " . $schema . ".tb_item as item on 
                         item.capitulo_fk = capitulo.id
                   inner join " . $schema . ".tb_recomendacao as recomendacao on 
                         recomendacao.item_fk = item.id 
                   left join " . $schema . ".tb_resposta as resposta on 
                         resposta.recomendacao_fk = recomendacao.id
                   inner join " . $schema . ".tb_unidade_administrativa as sureg on 
                         sureg.id = recomendacao.unidade_administrativa_fk
                   where relatorio.numero_relatorio is not null 
                     and relatorio.data_relatorio is not null 
                     and recomendacao.recomendacao_tipo_fk = (SELECT id FROM " . $schema . ".tb_recomendacao_tipo WHERE nome_tipo ilike '%recomendação%')
                     and (resposta.id = (select max(id) 
                                          from " . $schema . ".tb_resposta as aux 
                                         where aux.recomendacao_fk = resposta.recomendacao_fk)
                          OR 
                          (select max(id) 
                             from " . $schema . ".tb_resposta as aux 
                            where aux.recomendacao_fk = resposta.recomendacao_fk) is null
                          )
                     and not exists (select 1
                                       from " . $schema . ".tb_usuario as auditor 
                                      where auditor.nome_login = resposta.id_usuario_log)";

	$command = Yii::app()->db->createCommand($sql);
	$dados = $command->query();
	$relatorios = $dados->readAll();

	if (sizeof($relatorios)) {

		// configura parâmetros para enviar o e-mail
		$headers = "Reply-To: SIAUDI <".Yii::app()->params['adminEmail'].">\r\n";
		$headers .= "Return-Path: SIAUDI<".Yii::app()->params['adminEmail'].">\r\n";
		$headers .= "From: Unidade de Auditoria Interna <".Yii::app()->params['adminEmail'].">\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html;charset=iso-8859-1\r\n";
		$assunto = 'SIAUDI - Relatório sem Manifestação - Auditado';

		//sempre envia uma cópia para a gerência de auditoria
                //PORTAL_SPB e-mail para um grupo
                if(Yii::app()->params['emailGrupoAuditoria'] != '')
                    $vetor_email[]=Yii::app()->params['emailGrupoAuditoria'];
		if (sizeof($relatorios)){
			foreach ($relatorios as $vetor) {
				$data_inicio = date("d/m/Y", strtotime(is_null($vetor['data_resposta']) ? $vetor['data_relatorio'] : $vetor['data_resposta']));
				//primeira verificação: verifica se passou 20 dias úteis
				$data_final = Feriado::model()->DiasUteis($data_inicio, 20);
				$data_final = explode("/", $data_final);
				$data_final = $data_final[2] . $data_final[1] . $data_final[0];
				$hoje = date("Ymd");
	
				if ($hoje == $data_final) {
					$mensagem = "<html><head></head><body><font face='Verdana' size='2'>";
					$mensagem .= "Senhor(a) Gestor(a), informamos que foi ultrapassado, nesta data, o prazo de 20 dias úteis para que Vossa Senhoria informasse à
	                                  Unidade de Auditoria Interna sobre as providências corretivas adotadas à vista das recomendações constantes do 
	                                  Relatório de Auditoria nº " . $vetor[numero_relatorio] . ". Caso haja interesse de Vossa Senhoria, formalize pedido de 
	                                 prorrogação de prazo (até 20 dias úteis no máximo), devidamente justificado.";
					$mensagem .= "</font></body></html>";
				}
	
				//segunda verificação: verifica se passou 60 dias corridos
				$data = explode("/", $data_inicio);
				$dia = $data[0];
				$mes = $data[1];
				$ano = $data[2];
				$data_final = date('Y-m-d', mktime(0, 0, 0, $mes, $dia + 60, $ano));
				$data_final = explode("-", $data_final);
				$data_final = $data_final[0] . $data_final[1] . $data_final[2];
				$hoje = date("Ymd");
	
				if ($hoje == $data_final) {
					$mensagem = "<html><head></head><body><font face='Verdana' size='2'>";
					$mensagem .= "Senhor(a) Gestor(a), tendo sido ultrapassado, nesta data, o prazo máximo para que Vossa Senhoria informasse à
	                                  Unidade de Auditoria Interna sobre as providências corretivas adotadas à vista das recomendações constantes do 
	                                  Relatório de Auditoria nº " . $vetor[numero_relatorio] . ", na esteira do art. 49 da Lei nº 9.784, de 29 de janeiro de 1999, considerar-se-á que essa 
	                                  Unidade Organizacional auditada, na pessoa de seu(sua) Titular, está assumindo o risco de não corrigir a condição relatada pelos(as) 
	                                  profissionais auditores(as) internos(as), podendo incorrer oportunamente em responsabilização nas esferas administrativa, 
	                                  civil e penal em face de possível imputação de omissão e/ou de inércia administrativas por parte de instâncias fiscalizadoras e 
	                                  regulatórias competentes, na via de consequência (ref. Prática Recomendada/IPPF-IIA nº 2060-1).";
					$mensagem .= "</font></body></html>";
				}
                                
				if ($mensagem) {
					// Pega siaudi_clientes das Unidades Regionais do relatório
                                        //PORTAL_SPB identificar quem é o responsável pela unidade: é aquele que tiver perfil siaudi_cliente (151)
					$relatorioUnidadeAdm = RelatorioSureg::model()->findAllByAttributes(array('relatorio_fk' => $vetor['relatorio_fk']));
					if (sizeof($relatorioUnidadeAdm)){
						foreach ($relatorioUnidadeAdm as $ua) {
							// Pesquisa quem é siaudi_cliente da regional
							$sql_cliente = "SELECT vusuario.nome_login, vusuario.substituto_fk, vusuario.email
		                                    FROM " . $schema . ".tb_usuario vusuario 
		                                    WHERE (vusuario.unidade_administrativa_fk = '" . $ua->unidade_administrativa_fk . "')
		                                    AND (vusuario.perfil_fk = 151)";
                                                        //PORTAL_SPB alterada a forma de gerar e-mail
							$command = Yii::app()->db->createCommand($sql_cliente);
							$result = $command->query();
							$resultado = $result->readAll();
							if (sizeof($resultado)){
								foreach ($resultado as $vetor_cliente) {
									$email = $vetor_cliente[email];
									$vetor_email[] = $email;
								}
							}
						}
					}
					
					if (sizeof($vetor_email) > 1) {
						foreach ($vetor_email as $destinatario) {
							//$destinatario .= Yii::app()->params['dominioEmail'];
							$ok = $this->Envia_email($destinatario, $assunto, $mensagem, $headers);
						}
					}
				}
			}
		}
	}
}

// Altera campo st_libera_homologa da tb_relatorio
// para relatórios que já expiraram o prazo de manifestação
public function LiberaHomologa($id_relatorio) {
	$schema = Yii::app()->params['schema'];
	$sql = 'UPDATE ' . $schema . '.tb_relatorio set st_libera_homologa=1 where id=' . $id_relatorio;
	$command = Yii::app()->db->createCommand($sql);
	$command->execute();
}

// Consulta auditor chefe que finalizou ou homologou
// o relatório. Parâmetros de entrada:
// 1 => id do relatório
// 2 => pre_finaliza / finaliza / homologa
public function RelatorioAuditorChefe($id_relatorio, $acao) {
	$schema = Yii::app()->params['schema'];
	$sql = "  SELECT nome_usuario, nome_login, nome_cargo
                    FROM " . $schema . ".tb_usuario usuario 
                         INNER JOIN  " . $schema . ".tb_relatorio relatorio 
                               ON relatorio.login_" . $acao . "= usuario.nome_login 
                         LEFT OUTER JOIN
                               " . $schema . ".tb_cargo gerencia 
                               ON usuario.cargo_fk = gerencia.id
                    WHERE (relatorio.id=" . $id_relatorio . ") ";

	$command = Yii::app()->db->createCommand($sql);
	$result = $command->query();
	return ($result->read());
}

// Perfil Gerente, consulta todos os relatórios
// de acordo com o parâmetro de entrada
// tipo de relatório :: (1=> Não homo   logado, 2=> Homologado)
// ano => ex: 2012, mas pode ser nulo
public function RelatorioSaidaGerente($tipo_relatorio, $unidade_administrativa_fk, $ano = null) {
	$perfil = strtolower(Yii::app()->user->role);
	$perfil = str_replace("siaudi2", "siaudi", $perfil);
	$schema = Yii::app()->params['schema'];
	//        $tipo_relatorio = ($tipo_relatorio == 1) ? "IS NULL" : "IS NOT NULL";
	$sql_ano = ($ano) ? " AND  date_part('year', data_relatorio)=" . $ano : "";

	// consulta padrão para perfil siaudi_auditor (mostra
	// todos os relatórios de auditoria) -> verificar se continuará assim
	$sql = "SELECT relatorio.id, relatorio.numero_relatorio, relatorio.data_relatorio, e.sigla_auditoria
        FROM " . $schema . ".tb_relatorio relatorio 
            LEFT JOIN  " . $schema . ".tb_especie_auditoria e ON relatorio.especie_auditoria_fk = e.id 
            INNER JOIN  " . $schema . ".tb_relatorio_sureg rs ON relatorio.id = rs.relatorio_fk ";

	switch ($tipo_relatorio) {
		//não homologados
		case 1:
			$sql .= "WHERE data_relatorio IS NULL
                           AND numero_relatorio IS NULL";
			break;
			//homologados
		case 2:
			$sql .= "WHERE data_relatorio IS NOT NULL
                           AND numero_relatorio IS NOT NULL";
			break;
			//pré-finalizados
		case 3:
			$sql .= "WHERE data_pre_finalizado IS NOT NULL
                           AND login_pre_finaliza IS NOT NULL
                           AND numero_relatorio IS NULL
                           AND data_relatorio IS NULL
                           AND data_finalizado IS NULL 
                           AND login_finaliza IS NULL";
			break;
			//finalizados
		case 4:
			$sql .= "WHERE login_finaliza IS NOT NULL
                           AND data_finalizado IS NOT NULL 
                           AND numero_relatorio IS NULL
                           AND data_relatorio IS NULL";
			break;
	}

	$sql .= $sql_ano;

	if ($unidade_administrativa_fk != "") {
		$sql .= " and rs.unidade_administrativa_fk = " . $unidade_administrativa_fk;
	}

	if ($perfil == "siaudi_gerente_nucleo") {
		$sql .= " and nucleo is true";
	}
	
	$sql .= "        GROUP BY relatorio.id, relatorio.numero_relatorio, relatorio.data_relatorio, e.sigla_auditoria";
	$sql .= "        ORDER BY relatorio.data_relatorio DESC";
	
	$command = Yii::app()->db->createCommand($sql);
	$result = $command->query();
	return($result->readAll());
}

// Monta combo apenas com relatórios homologados
public function ComboRelatorioHomologado() {

	/*
	 * Deve ser mostrado para o perfil cliente somente os relatórios
	 * Homologados em que o cliente esteja envolvido como auditado.
	 */
	$perfil = strtolower(Yii::app()->user->role);
	$perfil = str_replace("siaudi2", "siaudi", $perfil);
	if (strtolower($perfil) == 'siaudi_cliente') {
		$login = Yii::app()->user->login;
		$dados_relatorio_acesso = RelatorioAcesso::model()->findAll("nome_login='" . $login . "'");

		if (sizeof($dados_relatorio_acesso)) {
			$clausula_in = '';
			foreach ($dados_relatorio_acesso as $vetor) {

				$clausula_in .= $vetor['relatorio_fk'] . ',';
			}
			//retira a última vírgula
			$clausula_in = substr($clausula_in, 0, -1);

			$relatorios = Relatorio::model()->findAll('data_relatorio IS NOT NULL and id in (' . $clausula_in . ') ORDER BY data_relatorio DESC');
		}
	} else {
		$relatorios = Relatorio::model()->findAll('data_relatorio IS NOT NULL ORDER BY data_relatorio DESC');
	}
	if (sizeof($relatorios) > 0) {
		foreach ($relatorios as $vetor) {
			$vetor_saida[$vetor->id] = $vetor->numero_relatorio . " de " . $vetor->data_relatorio;
		}
	} else {
		$vetor_saida[0] = "Sem relatórios";
	}

	return($vetor_saida);
}

// Monta combo apenas com relatórios finalizados e não homologados
public function ComboRelatorioFinalizado() {
	$schema = Yii::app()->params['schema'];
	$sql = "SELECT relatorio.id,  e.sigla_auditoria
        FROM " . $schema . ".tb_relatorio relatorio 
            LEFT JOIN  " . $schema . ".tb_especie_auditoria e ON relatorio.especie_auditoria_fk = e.id 
        WHERE (relatorio.data_relatorio IS NULL and relatorio.st_libera_homologa=1)
        ORDER BY relatorio.id DESC";

	$command = Yii::app()->db->createCommand($sql);
	$result = $command->query();
	$resultado = $result->readAll();

	if (sizeof($resultado) > 0) {
		foreach ($resultado as $vetor) {
			$vetor_saida[$vetor[id]] = $vetor[id] . " - " . $vetor[sigla_auditoria];
		}
	} else {
		$vetor_saida[0] = "Sem relatórios para homologar";
	}
	return($vetor_saida);
}

// Prorroga relatório homologado - acrescenta os dias
// úteis e envia e-mail avisando às partes interessadas
// Parâmetros de entrada: id do relatório homologado,
// e número de dias úteis a prorrogar.
public function ProrrogarRelatorioHomologado($id, $dias_uteis) {
	$schema = Yii::app()->params['schema'];
	$relatorio = Relatorio::model()->findByPk($id);


	// ---------------------------------------------
	// passo 1 - Altera o prazo no banco
	// ---------------------------------------------
	$prazo_atual = $relatorio[valor_prazo];
	$prazo_novo = $prazo_atual + $dias_uteis;
	$sql = "UPDATE " . $schema . ".tb_relatorio
                    SET valor_prazo=" . $prazo_novo . "
                    WHERE id=" . $id;
	$command = Yii::app()->db->createCommand($sql);
	$result = $command->query();

	// ---------------------------------------------
	// passo 2 - Pegar e-mail dos envolvidos
	// ---------------------------------------------
	// pega os auditores do relatório
        //PORTAL_SPB alterada a forma de gerar e-mail
	$relatorio_auditor = RelatorioAuditor::model()->findAllByAttributes(array('relatorio_fk' => $id));
	if (sizeof($relatorio_auditor)){
		foreach ($relatorio_auditor as $vetor) {
			$auditor = Usuario::model()->findByAttributes(array('id' => $vetor->usuario_fk));
			$vetor_email[] = $auditor->email;
		}
	}

	// pega os gerentes do relatório
	$relatorio_gerente = RelatorioGerente::model()->findAllByAttributes(array('relatorio_fk' => $id));
	if (sizeof($relatorio_gerente)){
		foreach ($relatorio_gerente as $vetor) {
			$gerente = Usuario::model()->findByAttributes(array('id' => $vetor->usuario_fk));
			$vetor_email[] = $gerente->email;
		}
	}

	// Pega e-mails das unidades administrativas (auditados) do relatório
//	$relatorioUnidadeAdm = RelatorioSureg::model()->findAllByAttributes(array('relatorio_fk' => $id));
//	if (sizeof($relatorioUnidadeAdm)){
//		foreach ($relatorioUnidadeAdm as $vetor) {
//			$unidade_administrativa = UnidadeAdministrativa::model()->findByAttributes(array('id' => $vetor->unidade_administrativa_fk));
//			// limpa caracterre º
//			$unidade_sigla = str_replace("º", "", $unidade_administrativa->sigla);
//			// considere somente valores até a barra. Exemplo: CPL / AM => CPL
//			$barra = strpos($unidade_sigla, "/");
//			if ($barra) {
//				$unidade_sigla = substr($unidade_sigla, 0, $barra);
//			}
//			$unidade_sigla = trim($unidade_sigla);
//			$vetor_email[] = strtolower($unidade_sigla); // no final, vai ficar sigla"@dominio";
//		}
//	}

	// ---------------------------------------------
	// passo 3 - Envia e-mail dos envolvidos
	// ---------------------------------------------
	// configura parâmetros para enviar o e-mail
	$headers = "Reply-To: SIAUDI <".Yii::app()->params['adminEmail'].">\r\n";
	$headers .= "Return-Path: SIAUDI <".Yii::app()->params['adminEmail'].">\r\n";
	$headers .= "From: Unidade de Auditoria Interna <".Yii::app()->params['adminEmail'].">\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html;charset=iso-8859-1\r\n";
	$assunto = 'SIAUDI - Prazo do Relatório Prorrogado';

	// formata texto html
	$nova_data = Feriado::model()->DiasUteis($relatorio[data_relatorio], $prazo_novo);
	$mensagem = "<html><head></head><body><font face='Verdana' size='2'>";
	$mensagem.= "Conforme solicitação, o prazo para resposta do <strong>Relatório " .
                "de Auditoria Nº " . $relatorio[numero_relatorio] .
                " de " . $relatorio[data_relatorio] . "</strong> foi " .
                "prorrogado para a data <strong>" . $nova_data . "</strong>.";
	$mensagem .= "</font></body></html>";

	// envia e-mails
        //PORTAL_SPB alterada a forma de gerar e-mail
	if (sizeof($vetor_email)){
		foreach ($vetor_email as $vetor) {
			// verifica se o e-mail para este relatório já foi enviado
			// para evitar mais de 1 envio do mesmo relatório para o mesmo
			// destinarário
			if (!$check_email[$vetor]) {
				$check_email[$vetor] = 1;
				$destinatario = $vetor;// . Yii::app()->params['dominioEmail'];
				$ok = $this->Envia_email($destinatario, $assunto, $mensagem, $headers);
			}
		}
	}
}

// Homologa o relatório
// Envia e-mail avisando às partes interessadas
// Parâmetros de entrada: id do relatório a homologar
public function HomologarRelatorio($id) {
	$schema = Yii::app()->params['schema'];
	$relatorio = Relatorio::model()->findByPk($id);
	// só executa se relatório ainda não foi homologado
	if (!$relatorio->data_relatorio) {
		$login = Yii::app()->user->login;
		// -----------------------------------------------
		// passo 1 - Identificar o número do relatório
		// (sequencia é feita conforme a categoria - orginária
		//  ou extraordinaria) 
                //  e insere as informações no banco
		// -----------------------------------------------
		$categoria = $relatorio->categoria_fk;
		$sql = "SELECT numero_relatorio
                   FROM " . $schema . ".tb_relatorio 
                   WHERE date_part('year', data_relatorio)=" . date("Y") . "AND
                         categoria_fk =" . $categoria . " and 
                         numero_relatorio IS NOT NULL
                   order by numero_relatorio DESC";
		$command = Yii::app()->db->createCommand($sql);
		$result = $command->query();
		$resultado = $result->read();
		$numero_relatorio = $resultado[numero_relatorio];
		$numero_relatorio = ($numero_relatorio) ? $numero_relatorio : 0;

		// insere dados do relatório no banco
		$sql = "UPDATE " . $schema . ".tb_relatorio
                    SET login_homologa='" . $login . "',
                        valor_prazo=20,
                        numero_relatorio=" . ($numero_relatorio + 1) . ",
                        data_relatorio='" . date("Y-m-d") . "'       
                    WHERE id=" . $id;
		$command = Yii::app()->db->createCommand($sql);
		$result = $command->query();


		// -----------------------------------------------------
		// passo 2 - Identificar o número do último item
		// (sequencia é ÚNICA independente da categoria - ordinária
		//  ou extraordinaria) e insere as informações no banco
		// -----------------------------------------------------
		$sql = "SELECT numero_item
                   FROM " . $schema . ".tb_item item
                   LEFT JOIN  " . $schema . ".tb_capitulo capitulo ON capitulo.id = item.capitulo_fk                        
                   LEFT JOIN  " . $schema . ".tb_relatorio relatorio ON relatorio.id= capitulo.relatorio_fk 
                   WHERE numero_item IS NOT NULL
                   order by item.id DESC";
		$command = Yii::app()->db->createCommand($sql);
		$result = $command->query();
		$resultado = $result->read();
		$numero_item = $resultado[numero_item];
		$numero_item = ($numero_item) ? $numero_item : 0;

		// verifica se o botão "Reiniciar contagem" foi marcado.
		// Em caso afirmativo, número do item deve ser 0.
		$RelatorioReiniciar = RelatorioReiniciar::model()->VerificaReiniciarContagem();
		if (sizeof($RelatorioReiniciar) > 0) {
			$numero_item = 0;
			$RelatorioReiniciar = RelatorioReiniciar::model()->DesabilitarReiniciarContagem();
		}


		// pega todos os capítulos para numerar os itens internos
		$capitulo = Capitulo::model()->findAll('relatorio_fk=' . $id . ' order by id ASC');
		if (sizeof($capitulo) > 0) {
			foreach ($capitulo as $vetor_capitulo) {
				// para cada capítulo, recupera o item por ordem de id
				$item = Item::model()->findAll('capitulo_fk=' . $vetor_capitulo->id . ' order by id ASC');
				if (sizeof($item) > 0) {
					foreach ($item as $vetor_item) {
						// altera sequencia do item no banco
						$numero_item++;
						$sql = "UPDATE " . $schema . ".tb_item SET numero_item=" . $numero_item . "WHERE id=" . $vetor_item->id;
						$command = Yii::app()->db->createCommand($sql);
						$result = $command->query();

						// busca pelas recomendações do item para numerar
						$numero_recomendacao = 0;
						$recomendacao = Recomendacao::model()->findAll('item_fk=' . $vetor_item->id . " order by id ASC");
						if (sizeof($recomendacao) > 0) {
							foreach ($recomendacao as $vetor_recomendacao) {
								$numero_recomendacao++;
								$sql = "UPDATE " . $schema . ".tb_recomendacao SET numero_recomendacao=" . $numero_recomendacao . "WHERE id=" . $vetor_recomendacao->id;
								$command = Yii::app()->db->createCommand($sql);
								$result = $command->query();
							}
						}
					}
				}
			}
		}



		// ---------------------------------------------
		// passo 3 - Pegar e-mail dos envolvidos
		// ---------------------------------------------
		// pega os auditores do relatório
                //PORTAL_SPB alterada a forma de gerar e-mail
		$relatorio_auditor = RelatorioAuditor::model()->findAllByAttributes(array('relatorio_fk' => $id));
		if (sizeof($relatorio_auditor)){
			foreach ($relatorio_auditor as $vetor) {
				$auditor = Usuario::model()->findByAttributes(array('id' => $vetor->usuario_fk));
				$vetor_email[] = $auditor->email;
			}
		}

		// pega os gerentes do relatório
		$relatorio_gerente = RelatorioGerente::model()->findAllByAttributes(array('relatorio_fk' => $id));
		if (sizeof($relatorio_gerente)){
			foreach ($relatorio_gerente as $vetor) {
				$gerente = Usuario::model()->findByAttributes(array('id' => $vetor->usuario_fk));
				$vetor_email[] = $gerente->email;
			}
		}

		// Pega e-mails das unidades administrativas (auditados) do relatório
//		$relatorioUnidadeAdm = RelatorioSureg::model()->findAllByAttributes(array('relatorio_fk' => $id));
//		if(sizeof($relatorioUnidadeAdm)){
//			foreach ($relatorioUnidadeAdm as $vetor) {
//				$unidade_administrativa = UnidadeAdministrativa::model()->findByAttributes(array('id' => $vetor->unidade_administrativa_fk));
//				// limpa caracterre º
//				$unidade_sigla = str_replace("º", "", $unidade_administrativa->sigla);
//				// considere somente valores até a barra. Exemplo: CPL / AM => CPL
//				$barra = strpos($unidade_sigla, "/");
//				if ($barra) {
//					$unidade_sigla = substr($unidade_sigla, 0, $barra);
//				}
//				$unidade_sigla = trim($unidade_sigla);
//				$vetor_email[] = strtolower($unidade_sigla); // no final, vai ficar sigla"@dominio";
//			}
//		}

		// ---------------------------------------------
		// passo 4 - Envia e-mail dos envolvidos
		// ---------------------------------------------
		// configura parâmetros para enviar o e-mail
		$headers = "Reply-To: SIAUDI <".Yii::app()->params['adminEmail'].">\r\n";
		$headers .= "Return-Path: SIAUDI<".Yii::app()->params['adminEmail'].">\r\n";
		$headers .= "From: Unidade de Auditoria Interna <".Yii::app()->params['adminEmail'].">\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html;charset=iso-8859-1\r\n";
		$assunto = 'SIAUDI - Relatório Homologado';

		// formata texto html
		$relatorio = Relatorio::model()->findByPk($id);
		$mensagem = "<html><head></head><body><font face='Verdana' size='2'>";
		$mensagem.= "Informamos que o <strong>Relatório de Auditoria Id Relatório: " . $id . "</strong> foi homologado pela Auditoria Interna e passou a ser identificado como <strong>Relatório de Auditoria Nº " . $relatorio->numero_relatorio . "/" . date("Y") . "</strong><br><br>";
		$mensagem.= "Cumpre à Unidade Organizacional auditada se manifestar formalmente acerca das recomendações e sugestões da Unidade de Auditoria Interna, <strong>no prazo de 20(vinte) dias úteis</strong>, após a data de recebimento, em atenção ao que explicita o Manual de Auditoria Interna.<br><br>";
		$mensagem.= "Acesse o Relatório de Auditoria via SIAUDI.";
		$mensagem.= "</font></body></html>";

		// envia e-mails
                //PORTAL_SPB alterada a forma de gerar e-mail
		if (sizeof($vetor_email)){
			foreach ($vetor_email as $vetor) {
				// verifica se o e-mail para este relatório já foi enviado
				// para evitar mais de 1 envio do mesmo relatório para o mesmo
				// destinarário
				if (!$check_email[$vetor]) {
					$check_email[$vetor] = 1;
					$destinatario = $vetor;//. Yii::app()->params['dominioEmail'];
					$ok = $this->Envia_email($destinatario, $assunto, $mensagem, $headers);
				}
			}
		}
	}
}

// método para centralizar o envio de e-mails
// (para facilitar o controle de comentar/descomentar
//  nos ambientes de desenvolvimento e produção)
public function Envia_email($destinatario, $assunto, $mensagem, $headers) {
	//$ok = mail($destinatario, $assunto, $mensagem, $headers);
}

// método para buscar os relatórios pendentes da página inicial
// (index) de acordo com o tipo de pendentes (1=todos, 2=meus clientes -
// envolvidos com o relatório de um auditor específico)
public function Relatorio_pendentes_index($tipo_pendentes) {
        $id_und_adm = Yii::app()->user->id_und_adm;
	$login = Yii::app()->user->login;
	$schema = Yii::app()->params['schema'];
	$perfil = Yii::app()->user->role;
	$perfil = str_replace("siaudi2", "siaudi", $perfil);
        

	//ESTA CONSULTA SERÁ EXECUTADA PARA TODOS OS PERFIS DE DIRETOR
	$sql_rel = "SELECT DISTINCT relatorio.id,
                           relatorio.numero_relatorio, 
                           relatorio.data_relatorio,
                           especie_auditoria.nome_auditoria,
                           relatorio.valor_prazo
                      FROM " . $schema . ".tb_relatorio relatorio 
                     INNER JOIN " . $schema . ".tb_especie_auditoria especie_auditoria ON 
                           relatorio.especie_auditoria_fk = especie_auditoria.id
                     INNER JOIN " . $schema . ".tb_relatorio_diretoria relatorio_diretoria ON 
                           relatorio_diretoria.relatorio_fk = relatorio.id AND
                           relatorio_diretoria.diretoria_fk = $id_und_adm 
                     WHERE (relatorio.numero_relatorio IS NOT NULL) 
                       AND (relatorio.data_regulariza IS NULL) 
                     ORDER BY relatorio.data_relatorio DESC, relatorio.numero_relatorio DESC ";
                    // data antiga -> ORDER BY date_part('year', relatorio.data_relatorio) DESC, relatorio.numero_relatorio DESC ";


	if ($perfil == "siaudi_auditor") {
		if (($tipo_pendentes == "2") || ($tipo_pendentes == "")) {//somente relatórios do auditor (pendências dos meus clientes)
			$sql_rel_tipo = " FROM  " . $schema . ".tb_relatorio relatorio INNER JOIN
                                                " . $schema . ".tb_relatorio_auditor relatorio_auditor ON relatorio.id=relatorio_auditor.relatorio_fk INNER JOIN
                                                " . $schema . ".tb_usuario usuario ON relatorio_auditor.usuario_fk= usuario.id INNER JOIN
                                                " . $schema . ".tb_especie_auditoria especie_auditoria ON relatorio.especie_auditoria_fk = especie_auditoria.id
                                          WHERE (relatorio.numero_relatorio IS NOT NULL) 
                                                AND (relatorio.data_regulariza IS NULL)
                                                AND (usuario.nome_login= '" . $login . "') ";
		} else { //todas as pendências
			$sql_rel_tipo = " FROM " . $schema . ".tb_relatorio relatorio INNER JOIN
                                               " . $schema . ".tb_especie_auditoria especie_auditoria ON relatorio.especie_auditoria_fk=especie_auditoria.id
                                          WHERE (relatorio.numero_relatorio IS NOT NULL) 
                                                AND (relatorio.data_regulariza IS NULL) ";
		}

		$sql_rel = " SELECT DISTINCT  relatorio.id,
                                    relatorio.numero_relatorio, 
                                    relatorio.data_relatorio, 
                                    especie_auditoria.nome_auditoria,
                                    relatorio.valor_prazo
                                    " . $sql_rel_tipo . "
                                    ORDER BY relatorio.data_relatorio DESC, relatorio.numero_relatorio DESC ";
                                    // data antiga -> ORDER BY date_part('year', relatorio.data_relatorio) DESC, relatorio.numero_relatorio DESC ";
	}

	if ($perfil == "siaudi_gerente" || $perfil == "siaudi_chefe_auditoria" || $perfil == "siaudi_cgu" || $perfil == "siaudi_gabin") {
		$sql_rel = " SELECT DISTINCT  relatorio.id,
                                    relatorio.numero_relatorio, 
                                    relatorio.data_relatorio,
                                    especie_auditoria.nome_auditoria,
                                    relatorio.valor_prazo	
                            FROM " . $schema . ".tb_relatorio relatorio INNER JOIN
                                 " . $schema . ".tb_especie_auditoria especie_auditoria ON relatorio.especie_auditoria_fk = especie_auditoria.id
                            WHERE (relatorio.numero_relatorio IS NOT NULL) 
                                    AND (relatorio.data_regulariza IS NULL)
                                    ORDER BY relatorio.data_relatorio DESC, relatorio.numero_relatorio DESC ";
                                    // data antiga -> ORDER BY date_part('year', relatorio.data_relatorio) DESC, relatorio.numero_relatorio DESC ";
	}

	if ($perfil == "siaudi_gerente_nucleo") {
		$sql_rel = " SELECT DISTINCT  relatorio.id,
                                    relatorio.numero_relatorio, 
                                    relatorio.data_relatorio,
                                    especie_auditoria.nome_auditoria,
                                    relatorio.valor_prazo	
                            FROM " . $schema . ".tb_relatorio relatorio INNER JOIN
                                 " . $schema . ".tb_especie_auditoria especie_auditoria ON relatorio.especie_auditoria_fk = especie_auditoria.id
                            WHERE (relatorio.numero_relatorio IS NOT NULL) 
                                    AND (relatorio.data_regulariza IS NULL)
                                    AND (relatorio.nucleo IS TRUE)
                                    ORDER BY relatorio.data_relatorio DESC, relatorio.numero_relatorio DESC ";
                                    // data antiga -> ORDER BY date_part('year', relatorio.data_relatorio) DESC, relatorio.numero_relatorio DESC ";
	}

	if ($perfil == "siaudi_cliente") { 
		$sql_rel = " SELECT  DISTINCT relatorio.id,
                                    relatorio.numero_relatorio, 
                                    relatorio.data_relatorio,
                                    especie_auditoria.nome_auditoria,
                                    relatorio.valor_prazo
                            FROM    " . $schema . ".tb_relatorio relatorio INNER JOIN
                                    " . $schema . ".tb_especie_auditoria especie_auditoria ON relatorio.especie_auditoria_fk = especie_auditoria.id INNER JOIN
                                    " . $schema . ".tb_relatorio_acesso acesso_relatorio ON acesso_relatorio.relatorio_fk = relatorio.id 
                                    
                            WHERE (relatorio.numero_relatorio IS NOT NULL) 
                                    AND (relatorio.data_regulariza IS NULL) 
                                    AND (acesso_relatorio.nome_login = '" . $login . "')
                                    ORDER BY relatorio.data_relatorio DESC, relatorio.numero_relatorio DESC ";
                                    // data antiga -> ORDER BY date_part('year', relatorio.data_relatorio) DESC, relatorio.numero_relatorio DESC ";
	}


	if ($perfil == "siaudi_cliente_item") {
		$sql_rel = " SELECT DISTINCT relatorio.id,
                                  relatorio.numero_relatorio, 
                                  relatorio.data_relatorio,
                                  especie_auditoria.nome_auditoria,
                                  relatorio.valor_prazo
                           FROM " . $schema . ".tb_relatorio relatorio INNER JOIN
                                " . $schema . ".tb_capitulo capitulo ON relatorio.id=capitulo.relatorio_fk INNER JOIN
                                " . $schema . ".tb_item item ON capitulo.id= item.capitulo_fk INNER JOIN
                                " . $schema . ".tb_relatorio_acesso_item acesso_item ON item.id = acesso_item.item_fk INNER JOIN 
                                " . $schema . ".tb_especie_auditoria especie_auditoria ON relatorio.especie_auditoria_fk = especie_auditoria.id
                           WHERE (relatorio.numero_relatorio IS NOT NULL) 
                                AND (relatorio.data_regulariza IS NULL) 
                                AND (acesso_item.nome_login = '" . $login . "')
                            GROUP BY relatorio.id,relatorio.numero_relatorio, 
                                  relatorio.data_relatorio,
                                  especie_auditoria.nome_auditoria,
                                  relatorio.valor_prazo	                                    
                                    ORDER BY relatorio.data_relatorio DESC, relatorio.numero_relatorio DESC ";
                                    // data antiga -> ORDER BY date_part('year', relatorio.data_relatorio) DESC, relatorio.numero_relatorio DESC ";
	}

	$command = Yii::app()->db->createCommand($sql_rel);
	$result = $command->query();
	return ($result->readAll());
}

// método que dá continuidade à busca das recomendações pendentes,
// na página inicial(index). Recebe como parâmetro o id do relatório
public function Relatorio_pendentes_index2($relatorio_id) {
	$login = Yii::app()->user->login;
	$schema = Yii::app()->params['schema'];
	$perfil = Yii::app()->user->role;
	$perfil = str_replace("siaudi2", "siaudi", $perfil);

	if ($perfil == "siaudi_cliente_item") {
		$sql_from = " INNER JOIN " . $schema . ".tb_relatorio_acesso_item acesso_item ON acesso_item.item_fk= item.id ";

		$sql_where = $sql_where . " AND (acesso_item.nome_login= '" . $login . "') ";
	}

	$sql = " SELECT capitulo.numero_capitulo,
                            item.numero_item,
                            recomendacao.id, 
                            recomendacao.numero_recomendacao,
                            recomendacao.descricao_recomendacao
                    FROM " . $schema . ".tb_capitulo  capitulo INNER JOIN
                         " . $schema . ".tb_item item ON capitulo.id=item.capitulo_fk INNER JOIN
                         " . $schema . ".tb_recomendacao recomendacao ON item.id=recomendacao.item_fk
                         " . $sql_from . "					
                    WHERE (capitulo.relatorio_fk = '" . $relatorio_id . "')
                            AND (recomendacao.recomendacao_tipo_fk = (SELECT id FROM " . $schema . ".tb_recomendacao_tipo WHERE nome_tipo ilike '%recomendação%')) /*Recomendação*/
                            " . $sql_where . "					
                    ORDER BY item.numero_item, recomendacao.numero_recomendacao";
        
	$command = Yii::app()->db->createCommand($sql);
	$result = $command->query();
	return ($result->readAll());

}

// método que busca os relatórios pendentes para
// gerar a tela de relatório do menu relatório -> relatórios pendentes.
// recebe o ano exercício como entrada (ex: 2013)
public function Relatorio_pendentes_saida($exercicio) {
        $id_und_adm = Yii::app()->user->id_und_adm;
	$login = Yii::app()->user->login;
	$schema = Yii::app()->params['schema'];
	$perfil = Yii::app()->user->role;
	$perfil = str_replace("siaudi2", "siaudi", $perfil);

	if ($perfil == "siaudi_cliente") {
		$sql_from = " INNER JOIN " . $schema . ".tb_acesso_relatorio acesso_relatorio ON relatorio.id = acesso_relatorio.relatorio_fk";
		$sql_where = $sql_where . " AND (acesso_relatorio.nome_login = '" . $login . "') ";
	} else if ((string) strpos($perfil, "siaudi_diretor") === (string) 0){
		$sql_from = " INNER JOIN " . $schema . ".tb_relatorio_diretoria relatorio_diretoria ON relatorio.id = relatorio_diretoria.relatorio_fk ";
		$sql_where = " AND (relatorio_diretoria.diretoria_fk = $id_und_adm)";
	}

	$sql = "SELECT relatorio.id,
                                relatorio.numero_relatorio, 
                                relatorio.data_relatorio,
                                relatorio.categoria_fk
                        FROM " . $schema . ".tb_relatorio relatorio $sql_from
                        WHERE  ((date_part('year', relatorio.data_relatorio) = '" . $exercicio . "') or ((relatorio.data_regulariza is NULL) and (date_part('year', relatorio.data_relatorio) <= '" . $exercicio . "')))
                                AND (relatorio.numero_relatorio IS NOT NULL) AND (relatorio.data_regulariza IS NULL) $sql_where
                        ORDER BY date_part('year',relatorio.data_relatorio) DESC, relatorio.numero_relatorio ";
	
	$command = Yii::app()->db->createCommand($sql);
	$result = $command->query();
	return ($result->readAll());
}

/* Método para verificar se o relatório pode ser aberto em PDF.
 *  Regras para abrir em PDF são:
 *  Regra 1 - Para relatórios homologados: perfil de gerente, gerente_nucleo, coordenador, cliente ou cliente_item
 *                                                   siaudi_diretor
 *                                                   siaudi_cgu, siaudi_gabin.
 *  Regra 2 - Para relatórios não-homologados: perfil de gerente, gerente_nucleo, coordenador ou auditor.
 *
 * @data_relatorio (date): data de homologação do relatório (null para relatório não homologado)
 */

public function Relatorio_Autorizar_PDF($data_relatorio) {
	$perfil = strtolower(Yii::app()->user->role);
	$perfil = str_replace("siaudi2", "siaudi", $perfil);
	$autorizar_pdf = 0;
	// Verifica regra 1
	if ($data_relatorio && ($perfil == "siaudi_gerente" || $perfil == "siaudi_chefe_auditoria" || $perfil == "siaudi_cliente" || $perfil == "siaudi_cliente_item" || $perfil == "siaudi_gerente_nucleo")) {
		$autorizar_pdf = 1;
	}
	// Verifica regra 2
	if (!$data_relatorio && ($perfil == "siaudi_gerente" || $perfil == "siaudi_chefe_auditoria" || $perfil == "siaudi_auditor" || $perfil == "siaudi_gerente_nucleo")) {
		$autorizar_pdf = 1;
	}

	// Verifica regra 3
	if ($data_relatorio && ((string) strpos($perfil, "siaudi_diretor") === (string) 0)){
		$autorizar_pdf = 1;
	}

	return $autorizar_pdf;
}

/* Método para buscar os usuários que acessaram os relatórios (ou itens de relatório).
 * @parametros (array) - contém os parâmetros necessários (id do relatório - para clientes -, data de início e data de fim).
 */

public function RelatorioRegistrosAcessos($parametros) {
	$relatorio_id = $parametros["relatorio_id"];
	// converte datas do  período para formato americano
	$periodo_inicio = MyFormatter::converteData($parametros["periodo_inicio"]);
	$periodo_fim = MyFormatter::converteData($parametros["periodo_fim"]);

	$criteria = new CDbCriteria();

	// faz uma lista com o login dos auditores (além de gerentes e chefes de auditoria) cadastrados
	if ($parametros["filtro_acesso"] == "auditor") {
		$Auditores = Usuario::model()->findAll();
		if (sizeof($Auditores)){
			foreach ($Auditores as $vetor) {
				$vetor_logins[] = $vetor->nome_login;
			}
		}
	}

	// faz uma lista com todos os clientes que possuem acesso ao relatório e item de relatório específico
	if ($parametros["filtro_acesso"] == "cliente") {
		//Pega clientes do relatório
		$Clientes = RelatorioAcesso::model()->findAllbyAttributes(array('relatorio_fk' => $parametros["relatorio_id"]));
		if (sizeof($Clientes)){
			foreach ($Clientes as $vetor) {
				$vetor_logins[] = $vetor->nome_login;
			}
		}

		//Pega clientes de um item do relatório
		$schema = Yii::app()->params['schema'];

		$sql = "SELECT nome_login
                    FROM " . $schema . ".tb_relatorio_acesso_item
                    where item_fk IN (SELECT item.id
                                        FROM " . $schema . ".tb_item item 
                                          INNER JOIN " . $schema . ".tb_capitulo capitulo ON item.capitulo_fk = capitulo.id
                                          INNER JOIN " . $schema . ".tb_relatorio relatorio ON capitulo.relatorio_fk = relatorio.id
                                        WHERE  relatorio.id=" . $parametros[relatorio_id] . ")";
		$command = Yii::app()->db->createCommand($sql);
		$result = $command->query();
		$result = $result->readAll();
		// se existem clientes nos itens do relatório, então incrementa vetor de logins
		// logins na tabela tb_relatorio_acesso_item
		if (sizeof($result) > 0) {
			foreach ($result as $vetor) {
				$vetor_logins[] = $vetor[nome_login];
			}
		}
		// filtra relatório selecionado para perfil do cliente, ou relatórios em branco (em caso de acesso ao sistema)
		$criteria->addCondition(array("relatorio_fk=$parametros[relatorio_id]", "relatorio_fk IS NULL"), "OR");
	}

	$criteria->addInCondition("nome_login", $vetor_logins);
        
        
        if ($periodo_inicio==$periodo_fim){
            
        $criteria->addCondition("to_char(data_entrada,'YYYY-MM-DD')='" . $periodo_inicio . "'");
        } else {
	$criteria->addCondition("to_char(data_entrada,'YYYY-MM-DD')>='" . $periodo_inicio . "' AND to_char(data_entrada,'YYYY-MM-DD')<='" . $periodo_fim . "'");
        }
        
	$criteria->order = 'data_entrada DESC';

	$result = LogEntrada::model()->findAll($criteria);

	return ($result);
}

}
