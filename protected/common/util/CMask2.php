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
/**
 * Classe para adição ou remoção de máscaras de strings
 * 
 * O conteúdo da string não deve ser modificado além da inclusão da máscara.
 * 
 * Fez-se necessária a criação de uma nova classe devido ao crescimento 
 * desordenado da CMask, que passou, ao longo do tempo, a acomodar helpers de 
 * validação e tratamento com datas, dentre outros, que foram direcionados para 
 * outras classes chamadas CValidate e CTransform.
 * 
 * As mascaras de Data não foram migradas, para dar preferencia ao uso da classe 
 * DateTime nativa do PHP.
 * 
 * Ao criar um metodo, favor siga este processo:
 * 1. Verifique se o método que precisa já não existe, para não haver lógica duplicada
 * 2. Mantenha o mesmo padrão de nomenclatura dos métodos
 * 3. Verifique novamente se o método que precisa já não existe. :-)
 * 4. Evite ao máximo o acoplamento com outras classes.
 * 
 * @package \common\util
 */
class CMask2
{
    /**
     * Remove qualquer pontuacao da string e a retorna. Pode ser utilizada para
     * remover diversos tipos de mascara em strings.
     * 
     * Exemplo:
     * 
     *   entrada: CMask2::removeMascara('remover.todos,os[p]ontos/(p)ossi_veis\e;imagina-veis.')
     *   retorno: (string) 'removertodosospontospossiveiseimaginaveis'
     * 
     * @param string $string A ser substituida.
     * @return string Sem pontuacoes.
     */
    public static function removeMascara($string)
    {
        return preg_replace('/\pP+/i', '', $string);
    }

    /**
     * Adiciona a mascara de CNPJ '00.000.000/0000-00' em uma string com apenas 
     * os 14 numeros: '00000000000000'.
     * 
     * @param string $cnpj Sem formatacao
     * @return string|null CNPJ formatado conforme padrao ou nulo
     */
    public static function cnpj($cnpj)
    {
        if (strlen($cnpj) == 14) {
            return substr($cnpj, 0, 2) .'.'. substr($cnpj, 2, 3) .'.'. substr($cnpj, 5, 3) .'/'. substr($cnpj, 8, 4) .'-'. substr($cnpj, 12, 2);
        }
        return null;
    }

    /**
     * Adiciona a mascara de CPF '000.000.000-00' em uma string com apenas 
     * os 11 numeros: '00000000000'.
     * 
     * @param string $cpf Sem formatacao (11 caracteres)
     * @return string|null CPF formatado conforme padrao ou nulo
     */
    public static function cpf($cpf)
    {
        if (strlen($cpf) == 11) {
            return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
        }
        return null;
    }

    /**
     * Adiciona a mascara em strings com inteligencia para descobrir se deve 
     * formatar como CPF ou CNPJ.
     * 
     * @param string $string Sem formatacao (11 ou 14 caracteres)
     * @return string|null CPF ou CNPJ formatado conforme padrao ou nulo
     */
    public static function cpfOuCnpj($string)
    {
        if (strlen($string) == 11) {
            return self::cpf($string);
        }
        return self::cnpj($string);
    }

    /**
     * Adiciona a mascara de CEP '00.000-000' em uma string com apenas os 8
     * numeros: '00000000'.
     * 
     * @param string $cep Sem formatacao
     * @return string|null CEP formatado conforme padrao ou nulo
     */
    public static function cep($cep)
    {
        if (strlen($cep) == 8) {
            return substr($cep, 0, 2) . '.' . substr($cep, 2, 3) . '-' . substr($cep, 5, 3);
        }
        return null;
    }

    /**
     * Adiciona a mascara de CDA '00.0000.0000-0' em uma string com apenas os 11
     * numeros: '00000000000'.
     * 
     * @param string $cda Sem formatacao
     * @return string|null CDA formatado conforme padrao ou nulo
     */
    public static function cda($cda)
    {
        if (strlen($cda) == 11) {
            return substr($cda, 0, 2) . '.' . substr($cda, 2, 4) . '.' . substr($cda, 6, 4) . '-' . substr($cda, 10, 1);
        }
        return null;
    }

    /**
     * Adiciona a mascara de CPR '0000.00.0000' em uma string com apenas os 10
     * numeros: '0000000000'.
     * 
     * @param string $cpr Sem formatacao
     * @return string|null CPR formatado conforme padrao ou nulo
     */
    public static function cpr($cpr)
    {
        if (strlen($cpr) == 10) {
            return substr($cpr, 0, 4) . '.' . substr($cpr, 4, 2) . '.' . substr($cpr, 6, 4);
        }
        return null;
    }

    /**
     * Adiciona a mascara de telefone nos formatos suportados abaixo:
     * 
     * - 08 caracteres: 1234-5678;
     * - 09 caracteres: 12345-6789;
     * - 10 caracteres: (12) 3456-7890;
     * - 11 caracteres: (12) 34567-8901;
     * 
     * @param string $telefone Sem formatacao
     * @return string|null Telefone formatado conforme padrao ou nulo
     */
    public static function telefone($telefone)
    {
        if (strlen($telefone) < 7 && strlen($telefone) > 12) {
            return null;
        }

        $retorno = '';
        if (strlen($telefone) >= 10) {
            $retorno .= '(' . substr($telefone, 0, 2) . ') ';
            $retorno .= substr($telefone, 2, strlen($telefone)-6);
        } else {
            $retorno .= substr($telefone, 0, strlen($telefone)-4);
        }
        $retorno .= '-' . substr($telefone, -4);
        return $retorno;
    }

    /**
     * Adiciona a mascara de valor nos formatos suportados abaixo:
     * 
     * - pt-BR: 9.999.999,99;
     * - en-US: 9,999,999.99;
     * 
     * em um ponto flutuante passado, retornando uma string.
     * 
     * @param float $valor Sem formatacao
     * @param string $formato Formato a ser gerado. Opcional, padrão pt-BR.
     * @return string Valor formatado conforme padrao ou nulo
     */
    public static function valor($valor, $formato = 'pt-BR')
    {
        switch ($formato) {
            case 'en-US':
                return (string) number_format($valor, 2, ".", ",");
                break;
            case 'pt-BR':
            default:
                return (string) number_format($valor, 2, ",", ".");
                break;
        }
    }

    /**
     * Adiciona a mascara de safra '0000/0000' em uma string com apenas os 8
     * numeros: '00000000'.
     * Não parece ser útil, mas foi migrada da antiga CMask.
     * 
     * @param string $safra Sem formatacao
     * @return string|null Safra formatada conforme padrao ou nulo
     */
    public static function safra($safra)
    {
        if (strlen($safra) == 8) {
            return substr($safra, 0, 4) . '/' . substr($safra, 4, 4);
        }
        return null;
    }

    /**
     * Adiciona a mascara de processo da Conab '00000.000000/0000-00' em uma 
     * string com os 17 numeros.
     * 
     * @param string $processo Sem formatacao
     * @return string|null Processo formatado conforme padrao ou nulo
     */
    public static function processoConab($processo)
    {
        if (strlen($processo) == 17) {
            return substr($processo, 0, 5) . '.' . substr($processo, 5, 6) . '/' .
                   substr($processo, 11, 4) . '-' . substr($processo, 15, 2);
        }
        return null;
    }

    /**
     * Adiciona a mascara de Chave de Acesso NFE no formato abaixo:
     * '00-0000-00.000.000/0000-00-00-000-000.000.000-0' 
     * em uma string com  os 44 numeros.
     * 
     * @param string $nfe Sem formatacao
     * @return string|null Chave Acesso NFE formatado conforme padrao ou nulo
     */
    public static function chaveAcessoNfe($nfe)
    {
        if (strlen($nfe) == 44) {
            return substr($nfe, 0, 2) . '-' . substr($nfe, 2, 4) . '-' . substr($nfe, 6, 2) . '.' .
                   substr($nfe, 8, 3) . '.' . substr($nfe, 11, 3) . '/' . substr($nfe, 14, 4) . '-' .
                   substr($nfe, 18, 2) . '-' . substr($nfe, 20, 2) . '-' . substr($nfe, 22, 3) . '-' .
                   substr($nfe, 25, 3) . '.' . substr($nfe, 28, 3) . '.' . substr($nfe, 31, 3) . '-' .
                   substr($nfe, 34, 3) . '.' . substr($nfe, 37, 3) . '.' . substr($nfe, 40, 3) . '-' .
                   substr($nfe, 43, 1);
        }
        return null;
    }

    /**
     * Adiciona a mascara de codigo de entrega CPR '000.000.000.00-0' em uma 
     * string com os 12 numeros.
     * 
     * @param string $codigo_entrega_cpr Sem formatacao
     * @return string|null Processo formatado conforme padrao ou nulo
     */
    public static function codigoEntregaCpr($codigo_entrega_cpr)
    {
        if (strlen($codigo_entrega_cpr) == 12) {
            return substr($codigo_entrega_cpr, 0, 3) . '.' . substr($codigo_entrega_cpr, 3, 3) . '.' .
                   substr($codigo_entrega_cpr, 6, 3) . '.' . substr($codigo_entrega_cpr, 9, 2) . '-' .
                   substr($codigo_entrega_cpr, 11, 1);
        }
        return null;
    }
}
