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
--
-- PostgreSQL database dump
--
-- sudo -u postgres psql -f siaudispb.sql -o /tmp/resultado.txt

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'LATIN1';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

CREATE USER usrsiaudi WITH ENCRYPTED PASSWORD '!@#-usr-siaudi';

--LC_CTYPE e LC_COLLATE são dependentes de como está configurado no UBUNTU
CREATE DATABASE bd_siaudi WITH TEMPLATE = template0 ENCODING = 'LATIN1' LC_COLLATE 'pt_BR' LC_CTYPE 'pt_BR';

--ALTER DATABASE bd_siaudi OWNER TO usrsiaudi;

\connect bd_siaudi

--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.6
-- Dumped by pg_dump version 9.3.5
-- Started on 2015-06-03 09:54:34 BRT

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'LATIN1';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- TOC entry 7 (class 2615 OID 17197)
-- Name: siaudi; Type: SCHEMA; Schema: -; Owner: usrsiaudi
--

CREATE SCHEMA siaudi;


ALTER SCHEMA siaudi OWNER TO usrsiaudi;

SET search_path = siaudi, pg_catalog;

--
-- TOC entry 171 (class 1259 OID 17198)
-- Name: raint_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE raint_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.raint_id_seq OWNER TO usrsiaudi;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 172 (class 1259 OID 17200)
-- Name: tb_acao; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_acao (
    id bigint NOT NULL,
    nome_acao character varying(1000) NOT NULL,
    valor_exercicio integer NOT NULL,
    especie_auditoria_fk integer NOT NULL,
    processo_fk integer NOT NULL,
    descricao_apresentacao text NOT NULL,
    descricao_escopo text NOT NULL,
    descricao_representatividade text NOT NULL,
    descricao_objetivo text NOT NULL,
    descricao_objetivo_estrategico text NOT NULL,
    descricao_origem character varying NOT NULL,
    descricao_resultados text NOT NULL,
    descricao_conhecimentos text NOT NULL,
    numero_acao integer
);


ALTER TABLE siaudi.tb_acao OWNER TO usrsiaudi;

--
-- TOC entry 2890 (class 0 OID 0)
-- Dependencies: 172
-- Name: TABLE tb_acao; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_acao IS 'Armazena as alteracoes de auditoria que serao vinculadas ao PAINT.';


--
-- TOC entry 173 (class 1259 OID 17206)
-- Name: tb_acao_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_acao_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_acao_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 2892 (class 0 OID 0)
-- Dependencies: 173
-- Name: tb_acao_id_seq; Type: SEQUENCE OWNED BY; Schema: siaudi; Owner: usrsiaudi
--

ALTER SEQUENCE tb_acao_id_seq OWNED BY tb_acao.id;


--
-- TOC entry 174 (class 1259 OID 17208)
-- Name: tb_acao_mes; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_acao_mes (
    acao_fk bigint NOT NULL,
    numero_mes smallint NOT NULL
);


ALTER TABLE siaudi.tb_acao_mes OWNER TO usrsiaudi;

--
-- TOC entry 2894 (class 0 OID 0)
-- Dependencies: 174
-- Name: TABLE tb_acao_mes; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_acao_mes IS 'Armazena os meses de cada ação.';


--
-- TOC entry 175 (class 1259 OID 17211)
-- Name: tb_acao_risco_pre; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_acao_risco_pre (
    acao_fk bigint NOT NULL,
    risco_pre_fk bigint NOT NULL
);


ALTER TABLE siaudi.tb_acao_risco_pre OWNER TO usrsiaudi;

--
-- TOC entry 2896 (class 0 OID 0)
-- Dependencies: 175
-- Name: TABLE tb_acao_risco_pre; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_acao_risco_pre IS 'Armazena os riscos pré-identificados de cada ação.';


--
-- TOC entry 176 (class 1259 OID 17214)
-- Name: tb_acao_sureg; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_acao_sureg (
    unidade_administrativa_fk bigint NOT NULL,
    acao_fk bigint NOT NULL
);


ALTER TABLE siaudi.tb_acao_sureg OWNER TO usrsiaudi;

--
-- TOC entry 2898 (class 0 OID 0)
-- Dependencies: 176
-- Name: TABLE tb_acao_sureg; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_acao_sureg IS 'Armazena as sureg''s de cada ação.';


--
-- TOC entry 177 (class 1259 OID 17217)
-- Name: tb_avaliacao_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_avaliacao_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_avaliacao_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 178 (class 1259 OID 17219)
-- Name: tb_avaliacao; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_avaliacao (
    id bigint DEFAULT nextval('tb_avaliacao_id_seq'::regclass) NOT NULL,
    relatorio_fk bigint NOT NULL,
    unidade_administrativa_fk bigint NOT NULL,
    usuario_fk bigint NOT NULL,
    data date NOT NULL,
    nome_login character varying(50)
);


ALTER TABLE siaudi.tb_avaliacao OWNER TO usrsiaudi;

--
-- TOC entry 2901 (class 0 OID 0)
-- Dependencies: 178
-- Name: TABLE tb_avaliacao; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_avaliacao IS 'Armazena a avaliação do auditor (somente cabeçalho contendo relatório, sureg e auditor vinculado).';


--
-- TOC entry 179 (class 1259 OID 17223)
-- Name: tb_avaliacao_auditor_fk_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_avaliacao_auditor_fk_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_avaliacao_auditor_fk_seq OWNER TO usrsiaudi;

--
-- TOC entry 2903 (class 0 OID 0)
-- Dependencies: 179
-- Name: tb_avaliacao_auditor_fk_seq; Type: SEQUENCE OWNED BY; Schema: siaudi; Owner: usrsiaudi
--

ALTER SEQUENCE tb_avaliacao_auditor_fk_seq OWNED BY tb_avaliacao.usuario_fk;


--
-- TOC entry 180 (class 1259 OID 17225)
-- Name: tb_avaliacao_criterio_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_avaliacao_criterio_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_avaliacao_criterio_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 181 (class 1259 OID 17227)
-- Name: tb_avaliacao_criterio; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_avaliacao_criterio (
    id bigint DEFAULT nextval('tb_avaliacao_criterio_id_seq'::regclass) NOT NULL,
    descricao_questao character varying(500) NOT NULL,
    valor_exercicio integer NOT NULL,
    numero_questao integer NOT NULL
);


ALTER TABLE siaudi.tb_avaliacao_criterio OWNER TO usrsiaudi;

--
-- TOC entry 182 (class 1259 OID 17231)
-- Name: tb_avaliacao_nota_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_avaliacao_nota_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_avaliacao_nota_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 183 (class 1259 OID 17233)
-- Name: tb_avaliacao_nota; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_avaliacao_nota (
    id bigint DEFAULT nextval('tb_avaliacao_nota_id_seq'::regclass) NOT NULL,
    avaliacao_fk bigint NOT NULL,
    avaliacao_criterio_fk bigint NOT NULL,
    nota smallint NOT NULL
);


ALTER TABLE siaudi.tb_avaliacao_nota OWNER TO usrsiaudi;

--
-- TOC entry 2908 (class 0 OID 0)
-- Dependencies: 183
-- Name: TABLE tb_avaliacao_nota; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_avaliacao_nota IS 'Armazena as notas das avaliações dos auditores.';


--
-- TOC entry 184 (class 1259 OID 17237)
-- Name: tb_avaliacao_observacao_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_avaliacao_observacao_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_avaliacao_observacao_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 185 (class 1259 OID 17239)
-- Name: tb_avaliacao_observacao; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_avaliacao_observacao (
    id bigint DEFAULT nextval('tb_avaliacao_observacao_id_seq'::regclass) NOT NULL,
    avaliacao_fk bigint NOT NULL,
    ds_observacao character varying(2056)
);


ALTER TABLE siaudi.tb_avaliacao_observacao OWNER TO usrsiaudi;

--
-- TOC entry 2911 (class 0 OID 0)
-- Dependencies: 185
-- Name: TABLE tb_avaliacao_observacao; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_avaliacao_observacao IS 'Armazena a observação das SUREGa na avaliação dos auditores. ';


--
-- TOC entry 186 (class 1259 OID 17246)
-- Name: tb_avaliacao_relatorio_fk_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_avaliacao_relatorio_fk_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_avaliacao_relatorio_fk_seq OWNER TO usrsiaudi;

--
-- TOC entry 2913 (class 0 OID 0)
-- Dependencies: 186
-- Name: tb_avaliacao_relatorio_fk_seq; Type: SEQUENCE OWNED BY; Schema: siaudi; Owner: usrsiaudi
--

ALTER SEQUENCE tb_avaliacao_relatorio_fk_seq OWNED BY tb_avaliacao.relatorio_fk;


--
-- TOC entry 187 (class 1259 OID 17248)
-- Name: tb_avaliacao_sureg_fk_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_avaliacao_sureg_fk_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_avaliacao_sureg_fk_seq OWNER TO usrsiaudi;

--
-- TOC entry 2915 (class 0 OID 0)
-- Dependencies: 187
-- Name: tb_avaliacao_sureg_fk_seq; Type: SEQUENCE OWNED BY; Schema: siaudi; Owner: usrsiaudi
--

ALTER SEQUENCE tb_avaliacao_sureg_fk_seq OWNED BY tb_avaliacao.unidade_administrativa_fk;


--
-- TOC entry 188 (class 1259 OID 17250)
-- Name: tb_capitulo_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_capitulo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_capitulo_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 189 (class 1259 OID 17252)
-- Name: tb_capitulo; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_capitulo (
    id bigint DEFAULT nextval('tb_capitulo_id_seq'::regclass) NOT NULL,
    relatorio_fk bigint NOT NULL,
    numero_capitulo character varying(5) NOT NULL,
    nome_capitulo character varying(200) NOT NULL,
    descricao_capitulo text,
    data_gravacao date,
    numero_capitulo_decimal integer
);


ALTER TABLE siaudi.tb_capitulo OWNER TO usrsiaudi;

--
-- TOC entry 190 (class 1259 OID 17259)
-- Name: tb_cargo; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_cargo (
    id bigint NOT NULL,
    nome_cargo character varying(100) NOT NULL
);


ALTER TABLE siaudi.tb_cargo OWNER TO usrsiaudi;

--
-- TOC entry 191 (class 1259 OID 17262)
-- Name: tb_cargo_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_cargo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_cargo_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 2920 (class 0 OID 0)
-- Dependencies: 191
-- Name: tb_cargo_id_seq; Type: SEQUENCE OWNED BY; Schema: siaudi; Owner: usrsiaudi
--

ALTER SEQUENCE tb_cargo_id_seq OWNED BY tb_cargo.id;


--
-- TOC entry 192 (class 1259 OID 17264)
-- Name: tb_categoria; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_categoria (
    id bigint NOT NULL,
    descricao_categoria character varying(50) NOT NULL
);


ALTER TABLE siaudi.tb_categoria OWNER TO usrsiaudi;

--
-- TOC entry 2922 (class 0 OID 0)
-- Dependencies: 192
-- Name: TABLE tb_categoria; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_categoria IS 'Tabela auxiliar que armazena as categorias do relatório.';


--
-- TOC entry 193 (class 1259 OID 17267)
-- Name: tb_categoria_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_categoria_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_categoria_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 2924 (class 0 OID 0)
-- Dependencies: 193
-- Name: tb_categoria_id_seq; Type: SEQUENCE OWNED BY; Schema: siaudi; Owner: usrsiaudi
--

ALTER SEQUENCE tb_categoria_id_seq OWNED BY tb_categoria.id;


--
-- TOC entry 194 (class 1259 OID 17269)
-- Name: tb_criterio; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_criterio (
    id bigint NOT NULL,
    valor_exercicio integer NOT NULL,
    nome_criterio character varying(200) NOT NULL,
    tipo_criterio_fk bigint NOT NULL,
    valor_peso smallint NOT NULL,
    descricao_criterio text
);


ALTER TABLE siaudi.tb_criterio OWNER TO usrsiaudi;

--
-- TOC entry 2926 (class 0 OID 0)
-- Dependencies: 194
-- Name: TABLE tb_criterio; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_criterio IS 'Tabela para armazenar os critérios. Ex: Avaliação de riscos, auditorias pretéritas, etc...';


--
-- TOC entry 195 (class 1259 OID 17275)
-- Name: tb_criterio_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_criterio_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_criterio_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 2928 (class 0 OID 0)
-- Dependencies: 195
-- Name: tb_criterio_id_seq; Type: SEQUENCE OWNED BY; Schema: siaudi; Owner: usrsiaudi
--

ALTER SEQUENCE tb_criterio_id_seq OWNED BY tb_criterio.id;


--
-- TOC entry 196 (class 1259 OID 17277)
-- Name: tb_diretoria; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_diretoria (
    id bigint NOT NULL,
    descricao_sigla_diretoria character varying(50) NOT NULL,
    descricao_diretoria character varying(200)
);


ALTER TABLE siaudi.tb_diretoria OWNER TO usrsiaudi;

--
-- TOC entry 2930 (class 0 OID 0)
-- Dependencies: 196
-- Name: TABLE tb_diretoria; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_diretoria IS 'Tabela que armazena os tipos possíveis de diretoria.';


--
-- TOC entry 197 (class 1259 OID 17280)
-- Name: tb_especie_auditoria; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_especie_auditoria (
    id integer NOT NULL,
    nome_auditoria character varying(200) NOT NULL,
    sigla_auditoria character varying(10) NOT NULL
);


ALTER TABLE siaudi.tb_especie_auditoria OWNER TO usrsiaudi;

--
-- TOC entry 2932 (class 0 OID 0)
-- Dependencies: 197
-- Name: TABLE tb_especie_auditoria; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_especie_auditoria IS 'Tabela auxiliar para escolha da espécie de auditoria. Ex: AE - Auditoria Especial.';


--
-- TOC entry 198 (class 1259 OID 17283)
-- Name: tb_especie_auditoria_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_especie_auditoria_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_especie_auditoria_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 2934 (class 0 OID 0)
-- Dependencies: 198
-- Name: tb_especie_auditoria_id_seq; Type: SEQUENCE OWNED BY; Schema: siaudi; Owner: usrsiaudi
--

ALTER SEQUENCE tb_especie_auditoria_id_seq OWNED BY tb_especie_auditoria.id;


--
-- TOC entry 199 (class 1259 OID 17285)
-- Name: tb_feriado; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_feriado (
    id bigint NOT NULL,
    data_feriado date NOT NULL,
    nome_feriado character varying(60) NOT NULL,
    repetir_todo_ano boolean
);


ALTER TABLE siaudi.tb_feriado OWNER TO usrsiaudi;

--
-- TOC entry 200 (class 1259 OID 17288)
-- Name: tb_feriado2_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_feriado2_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_feriado2_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 2937 (class 0 OID 0)
-- Dependencies: 200
-- Name: tb_feriado2_id_seq; Type: SEQUENCE OWNED BY; Schema: siaudi; Owner: usrsiaudi
--

ALTER SEQUENCE tb_feriado2_id_seq OWNED BY tb_feriado.id;


--
-- TOC entry 201 (class 1259 OID 17290)
-- Name: tb_funcao; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_funcao (
    id bigint NOT NULL,
    nome_funcao character varying(100) NOT NULL
);


ALTER TABLE siaudi.tb_funcao OWNER TO usrsiaudi;

--
-- TOC entry 202 (class 1259 OID 17293)
-- Name: tb_funcao_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_funcao_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_funcao_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 2940 (class 0 OID 0)
-- Dependencies: 202
-- Name: tb_funcao_id_seq; Type: SEQUENCE OWNED BY; Schema: siaudi; Owner: usrsiaudi
--

ALTER SEQUENCE tb_funcao_id_seq OWNED BY tb_funcao.id;


--
-- TOC entry 203 (class 1259 OID 17295)
-- Name: tb_homens_hora; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_homens_hora (
    id bigint NOT NULL,
    valor_exercicio integer NOT NULL,
    valor_asterisco character varying(10),
    usuario_fk integer NOT NULL,
    valor_horas_homem integer NOT NULL,
    valor_ferias integer NOT NULL,
    valor_lic_premio integer,
    valor_outros integer
);


ALTER TABLE siaudi.tb_homens_hora OWNER TO usrsiaudi;

--
-- TOC entry 2942 (class 0 OID 0)
-- Dependencies: 203
-- Name: TABLE tb_homens_hora; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_homens_hora IS 'Armazena a tabela de homens hora do PAINT';


--
-- TOC entry 204 (class 1259 OID 17298)
-- Name: tb_homens_hora_conf_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_homens_hora_conf_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_homens_hora_conf_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 205 (class 1259 OID 17300)
-- Name: tb_homens_hora_conf; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_homens_hora_conf (
    valor_exercicio integer NOT NULL,
    descricao_act character varying(1000) NOT NULL,
    id bigint DEFAULT nextval('tb_homens_hora_conf_id_seq'::regclass) NOT NULL
);


ALTER TABLE siaudi.tb_homens_hora_conf OWNER TO usrsiaudi;

--
-- TOC entry 2945 (class 0 OID 0)
-- Dependencies: 205
-- Name: TABLE tb_homens_hora_conf; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_homens_hora_conf IS 'Armazena as configurações da tabela de homens hora do PAINT';


--
-- TOC entry 206 (class 1259 OID 17304)
-- Name: tb_homens_hora_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_homens_hora_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_homens_hora_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 2947 (class 0 OID 0)
-- Dependencies: 206
-- Name: tb_homens_hora_id_seq; Type: SEQUENCE OWNED BY; Schema: siaudi; Owner: usrsiaudi
--

ALTER SEQUENCE tb_homens_hora_id_seq OWNED BY tb_homens_hora.id;


--
-- TOC entry 207 (class 1259 OID 17306)
-- Name: tb_imagem; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_imagem (
    id integer NOT NULL,
    recomendacao_fk bigint,
    arquivo_imagem character varying(200),
    tipo integer,
    login character varying(60),
    largura integer,
    altura integer
);


ALTER TABLE siaudi.tb_imagem OWNER TO usrsiaudi;

--
-- TOC entry 2949 (class 0 OID 0)
-- Dependencies: 207
-- Name: TABLE tb_imagem; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_imagem IS 'Tabela migrada do sistema legado em 12/08/2014';


--
-- TOC entry 208 (class 1259 OID 17309)
-- Name: tb_imagem_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_imagem_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_imagem_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 2951 (class 0 OID 0)
-- Dependencies: 208
-- Name: tb_imagem_id_seq; Type: SEQUENCE OWNED BY; Schema: siaudi; Owner: usrsiaudi
--

ALTER SEQUENCE tb_imagem_id_seq OWNED BY tb_imagem.id;


--
-- TOC entry 209 (class 1259 OID 17311)
-- Name: tb_item; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_item (
    id bigint NOT NULL,
    capitulo_fk bigint NOT NULL,
    numero_item bigint,
    nome_item character varying(100) NOT NULL,
    descricao_item text,
    data_gravacao date,
    valor_reais numeric,
    objeto_fk bigint DEFAULT 1 NOT NULL,
    valor_nao_se_aplica boolean
);


ALTER TABLE siaudi.tb_item OWNER TO usrsiaudi;

--
-- TOC entry 210 (class 1259 OID 17318)
-- Name: tb_item_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_item_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_item_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 2954 (class 0 OID 0)
-- Dependencies: 210
-- Name: tb_item_id_seq; Type: SEQUENCE OWNED BY; Schema: siaudi; Owner: usrsiaudi
--

ALTER SEQUENCE tb_item_id_seq OWNED BY tb_item.id;


--
-- TOC entry 211 (class 1259 OID 17320)
-- Name: tb_log_entrada; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_log_entrada (
    id bigint NOT NULL,
    nome_login character varying(100) NOT NULL,
    data_entrada timestamp without time zone NOT NULL,
    valor_ip character varying(23),
    relatorio_fk bigint,
    item_fk bigint
);


ALTER TABLE siaudi.tb_log_entrada OWNER TO usrsiaudi;

--
-- TOC entry 2956 (class 0 OID 0)
-- Dependencies: 211
-- Name: TABLE tb_log_entrada; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_log_entrada IS 'Tabela para armazenar as datas e horários de acesso dos usuários aos relatórios de auditoria.';


--
-- TOC entry 212 (class 1259 OID 17323)
-- Name: tb_log_entrada_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_log_entrada_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_log_entrada_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 213 (class 1259 OID 17325)
-- Name: tb_log_entrada_id_seq1; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_log_entrada_id_seq1
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_log_entrada_id_seq1 OWNER TO usrsiaudi;

--
-- TOC entry 2959 (class 0 OID 0)
-- Dependencies: 213
-- Name: tb_log_entrada_id_seq1; Type: SEQUENCE OWNED BY; Schema: siaudi; Owner: usrsiaudi
--

ALTER SEQUENCE tb_log_entrada_id_seq1 OWNED BY tb_log_entrada.id;


--
-- TOC entry 214 (class 1259 OID 17327)
-- Name: tb_manifestacao; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_manifestacao (
    id bigint NOT NULL,
    relatorio_fk bigint NOT NULL,
    nome_login character varying(100) NOT NULL,
    data_manifestacao date NOT NULL,
    descricao_manifestacao text,
    status_manifestacao smallint,
    descricao_resposta text,
    data_resposta date,
    unidade_administrativa_fk bigint,
    nome_login_resposta character varying(100)
);


ALTER TABLE siaudi.tb_manifestacao OWNER TO usrsiaudi;

--
-- TOC entry 2961 (class 0 OID 0)
-- Dependencies: 214
-- Name: TABLE tb_manifestacao; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_manifestacao IS 'Armazena a manifestação do relatório.';


--
-- TOC entry 215 (class 1259 OID 17333)
-- Name: tb_manifestacao_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_manifestacao_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_manifestacao_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 2963 (class 0 OID 0)
-- Dependencies: 215
-- Name: tb_manifestacao_id_seq; Type: SEQUENCE OWNED BY; Schema: siaudi; Owner: usrsiaudi
--

ALTER SEQUENCE tb_manifestacao_id_seq OWNED BY tb_manifestacao.id;


--
-- TOC entry 292 (class 1259 OID 21998)
-- Name: tb_menu_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_menu_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_menu_seq OWNER TO usrsiaudi;

--
-- TOC entry 293 (class 1259 OID 22000)
-- Name: tb_menu; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_menu (
    id integer DEFAULT nextval('tb_menu_seq'::regclass) NOT NULL,
    menu_pai_fk bigint,
    nivel integer,
    ordem integer,
    coluna integer DEFAULT 0,
    titulo character varying(50),
    descricao character varying(50),
    url character varying(100),
    imagem character varying(100),
    url_parametro character varying(200),
    sistema_fk integer
);


ALTER TABLE siaudi.tb_menu OWNER TO usrsiaudi;

--
-- TOC entry 2965 (class 0 OID 0)
-- Dependencies: 293
-- Name: TABLE tb_menu; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_menu IS 'Nesta entidade serao armazenados os Itens/Sub-Itens de Menu, dos sistemas';


--
-- TOC entry 2966 (class 0 OID 0)
-- Dependencies: 293
-- Name: COLUMN tb_menu.id; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON COLUMN tb_menu.id IS 'Chave Primaria da tabela (PK)';


--
-- TOC entry 2967 (class 0 OID 0)
-- Dependencies: 293
-- Name: COLUMN tb_menu.menu_pai_fk; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON COLUMN tb_menu.menu_pai_fk IS 'Chave Estrangeira para a propria tabela (FK de autorelacionamento)';


--
-- TOC entry 2968 (class 0 OID 0)
-- Dependencies: 293
-- Name: COLUMN tb_menu.nivel; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON COLUMN tb_menu.nivel IS 'Ordinal de sequencia do item de menu na distribuiÃ§Ã£o Vertical';


--
-- TOC entry 2969 (class 0 OID 0)
-- Dependencies: 293
-- Name: COLUMN tb_menu.ordem; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON COLUMN tb_menu.ordem IS 'Ordinal de sequencia do item de menu na distribuiÃ§Ã£o Horizontal';


--
-- TOC entry 2970 (class 0 OID 0)
-- Dependencies: 293
-- Name: COLUMN tb_menu.coluna; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON COLUMN tb_menu.coluna IS 'Ordinal de sequencia da distribuiÃ§Ã£o de itens em colunas';


--
-- TOC entry 2971 (class 0 OID 0)
-- Dependencies: 293
-- Name: COLUMN tb_menu.titulo; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON COLUMN tb_menu.titulo IS 'Nome (identificacao visual) do item de menu';


--
-- TOC entry 2972 (class 0 OID 0)
-- Dependencies: 293
-- Name: COLUMN tb_menu.descricao; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON COLUMN tb_menu.descricao IS 'Texto descritivo (hint ou alternate-text) do item de menu';


--
-- TOC entry 2973 (class 0 OID 0)
-- Dependencies: 293
-- Name: COLUMN tb_menu.url; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON COLUMN tb_menu.url IS 'Caminho (FQPN) ou Endereco (URL/URI) do mdulo/programa que o item de menu dispara';


--
-- TOC entry 2974 (class 0 OID 0)
-- Dependencies: 293
-- Name: COLUMN tb_menu.imagem; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON COLUMN tb_menu.imagem IS 'Caminho (FQPN) ou EndereÃ§o (URL/URI) de arquivo de imagem a ser associada ao item de menu';


--
-- TOC entry 2975 (class 0 OID 0)
-- Dependencies: 293
-- Name: COLUMN tb_menu.url_parametro; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON COLUMN tb_menu.url_parametro IS 'VariÃ¡vel/Dado a ser usado como ParÃ¢mentro (complemento) ao URL_MENU';


--
-- TOC entry 295 (class 1259 OID 22008)
-- Name: tb_menu_perfil; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_menu_perfil (
    menu_fk bigint NOT NULL,
    perfil_fk bigint NOT NULL
);


ALTER TABLE siaudi.tb_menu_perfil OWNER TO usrsiaudi;

--
-- TOC entry 2977 (class 0 OID 0)
-- Dependencies: 295
-- Name: TABLE tb_menu_perfil; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_menu_perfil IS 'Entidade associativa. Relaciona Menus ao Perfil';


--
-- TOC entry 296 (class 1259 OID 22011)
-- Name: tb_modulo_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_modulo_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_modulo_seq OWNER TO usrsiaudi;

--
-- TOC entry 297 (class 1259 OID 22013)
-- Name: tb_modulo; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_modulo (
    id bigint DEFAULT nextval('tb_modulo_seq'::regclass) NOT NULL,
    sistema_fk integer NOT NULL,
    nome character varying(100) NOT NULL,
    descricao character varying(250)
);


ALTER TABLE siaudi.tb_modulo OWNER TO usrsiaudi;

--
-- TOC entry 2979 (class 0 OID 0)
-- Dependencies: 297
-- Name: TABLE tb_modulo; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_modulo IS 'Nesta entidade serao registrados os Modulos/Programas de sistema, que terao restriÃ§oes.';


--
-- TOC entry 2980 (class 0 OID 0)
-- Dependencies: 297
-- Name: COLUMN tb_modulo.id; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON COLUMN tb_modulo.id IS 'Chave Primaria da tabela (PK)';


--
-- TOC entry 2981 (class 0 OID 0)
-- Dependencies: 297
-- Name: COLUMN tb_modulo.nome; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON COLUMN tb_modulo.nome IS 'Nome do modulo/programa (nome do arquivo/classe do modulo/programa sem extensao)';


--
-- TOC entry 2982 (class 0 OID 0)
-- Dependencies: 297
-- Name: COLUMN tb_modulo.descricao; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON COLUMN tb_modulo.descricao IS 'Descricao do modulo/programa';


--
-- TOC entry 216 (class 1259 OID 17335)
-- Name: tb_nucleo_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_nucleo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_nucleo_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 217 (class 1259 OID 17337)
-- Name: tb_nucleo; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_nucleo (
    id bigint DEFAULT nextval('tb_nucleo_id_seq'::regclass) NOT NULL,
    nome_nucleo character varying(200)
);


ALTER TABLE siaudi.tb_nucleo OWNER TO usrsiaudi;

--
-- TOC entry 218 (class 1259 OID 17341)
-- Name: tb_objeto_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_objeto_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_objeto_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 219 (class 1259 OID 17343)
-- Name: tb_objeto; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_objeto (
    id integer DEFAULT nextval('tb_objeto_id_seq'::regclass) NOT NULL,
    nome_objeto character varying(200) NOT NULL
);


ALTER TABLE siaudi.tb_objeto OWNER TO usrsiaudi;

--
-- TOC entry 2987 (class 0 OID 0)
-- Dependencies: 219
-- Name: TABLE tb_objeto; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_objeto IS 'Armazena os objetos do relatório e do item.';


--
-- TOC entry 220 (class 1259 OID 17347)
-- Name: tb_objeto_id_seq1; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_objeto_id_seq1
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_objeto_id_seq1 OWNER TO usrsiaudi;

--
-- TOC entry 2989 (class 0 OID 0)
-- Dependencies: 220
-- Name: tb_objeto_id_seq1; Type: SEQUENCE OWNED BY; Schema: siaudi; Owner: usrsiaudi
--

ALTER SEQUENCE tb_objeto_id_seq1 OWNED BY tb_objeto.id;


--
-- TOC entry 221 (class 1259 OID 17349)
-- Name: tb_paint; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_paint (
    id bigint NOT NULL,
    nome_titulo character varying(1024) NOT NULL,
    descricao_texto text NOT NULL,
    numero_sequencia integer NOT NULL,
    numero_item_pai integer,
    valor_exercicio integer,
    numero_pdf character varying(20),
    data_gravacao date
);


ALTER TABLE siaudi.tb_paint OWNER TO usrsiaudi;

--
-- TOC entry 222 (class 1259 OID 17355)
-- Name: tb_paint_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_paint_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_paint_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 2992 (class 0 OID 0)
-- Dependencies: 222
-- Name: tb_paint_id_seq; Type: SEQUENCE OWNED BY; Schema: siaudi; Owner: usrsiaudi
--

ALTER SEQUENCE tb_paint_id_seq OWNED BY tb_paint.id;


--
-- TOC entry 223 (class 1259 OID 17357)
-- Name: tb_perfil_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_perfil_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_perfil_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 224 (class 1259 OID 17359)
-- Name: tb_perfil; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_perfil (
    id bigint DEFAULT nextval('tb_perfil_id_seq'::regclass) NOT NULL,
    nome_perfil character varying(100) NOT NULL,
    nome_interno character varying(100) NOT NULL,
    sistema_fk bigint
);


ALTER TABLE siaudi.tb_perfil OWNER TO usrsiaudi;

--
-- TOC entry 225 (class 1259 OID 17363)
-- Name: tb_plan_especifico; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_plan_especifico (
    id bigint NOT NULL,
    valor_exercicio integer NOT NULL,
    observacao_representatividade text NOT NULL,
    observacao_amostragem text NOT NULL,
    data_log timestamp without time zone NOT NULL,
    id_usuario_log bigint NOT NULL,
    valor_sureg bigint,
    observacao_questoes_macro text,
    observacao_resultados text,
    observacao_legislacao text,
    observacao_detalhamento text,
    observacao_tecnicas_auditoria text,
    observacao_pendencias text,
    observacao_custos text,
    observacao_cronograma text,
    acao_fk bigint,
    unidade_administrativa_fk bigint,
    objeto_fk bigint DEFAULT 1 NOT NULL,
    data_inicio_atividade date DEFAULT ('now'::text)::date NOT NULL,
    escopo_acao text
);


ALTER TABLE siaudi.tb_plan_especifico OWNER TO usrsiaudi;

--
-- TOC entry 2996 (class 0 OID 0)
-- Dependencies: 225
-- Name: TABLE tb_plan_especifico; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_plan_especifico IS 'Tabela para armazenar o planejamento específico.';


--
-- TOC entry 226 (class 1259 OID 17371)
-- Name: tb_plan_especifico_auditor; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_plan_especifico_auditor (
    plan_especifico_fk bigint NOT NULL,
    usuario_fk bigint NOT NULL
);


ALTER TABLE siaudi.tb_plan_especifico_auditor OWNER TO usrsiaudi;

--
-- TOC entry 2998 (class 0 OID 0)
-- Dependencies: 226
-- Name: TABLE tb_plan_especifico_auditor; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_plan_especifico_auditor IS 'Armazena todos os auditores  de cada planejamento específico.';


--
-- TOC entry 227 (class 1259 OID 17374)
-- Name: tb_plan_especifico_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_plan_especifico_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_plan_especifico_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 3000 (class 0 OID 0)
-- Dependencies: 227
-- Name: tb_plan_especifico_id_seq; Type: SEQUENCE OWNED BY; Schema: siaudi; Owner: usrsiaudi
--

ALTER SEQUENCE tb_plan_especifico_id_seq OWNED BY tb_plan_especifico.id;


--
-- TOC entry 228 (class 1259 OID 17376)
-- Name: tb_processo_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_processo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_processo_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 229 (class 1259 OID 17378)
-- Name: tb_processo; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_processo (
    id integer DEFAULT nextval('tb_processo_id_seq'::regclass) NOT NULL,
    nome_processo character varying(200) NOT NULL,
    tipo_processo_fk integer NOT NULL,
    valor_exercicio integer NOT NULL,
    repetir_todo_ano boolean
);


ALTER TABLE siaudi.tb_processo OWNER TO usrsiaudi;

--
-- TOC entry 230 (class 1259 OID 17382)
-- Name: tb_processo_especie_auditoria; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_processo_especie_auditoria (
    processo_fk integer NOT NULL,
    especie_auditoria_fk integer NOT NULL
);


ALTER TABLE siaudi.tb_processo_especie_auditoria OWNER TO usrsiaudi;

--
-- TOC entry 231 (class 1259 OID 17385)
-- Name: tb_processo_risco_pre; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_processo_risco_pre (
    processo_fk bigint NOT NULL,
    risco_pre_fk bigint NOT NULL
);


ALTER TABLE siaudi.tb_processo_risco_pre OWNER TO usrsiaudi;

--
-- TOC entry 3005 (class 0 OID 0)
-- Dependencies: 231
-- Name: TABLE tb_processo_risco_pre; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_processo_risco_pre IS 'Armazena os riscos pré-identificados de cada processo.';


--
-- TOC entry 232 (class 1259 OID 17388)
-- Name: tb_raint_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_raint_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_raint_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 233 (class 1259 OID 17390)
-- Name: tb_raint; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_raint (
    id bigint DEFAULT nextval('tb_raint_id_seq'::regclass) NOT NULL,
    nome_titulo character varying(1024) NOT NULL,
    descricao_texto text NOT NULL,
    numero_sequencia integer NOT NULL,
    numero_item_pai integer,
    valor_exercicio integer,
    numero_pdf character varying(20),
    data_gravacao date
);


ALTER TABLE siaudi.tb_raint OWNER TO usrsiaudi;

--
-- TOC entry 234 (class 1259 OID 17397)
-- Name: tb_raint_id_seq1; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_raint_id_seq1
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_raint_id_seq1 OWNER TO usrsiaudi;

--
-- TOC entry 3009 (class 0 OID 0)
-- Dependencies: 234
-- Name: tb_raint_id_seq1; Type: SEQUENCE OWNED BY; Schema: siaudi; Owner: usrsiaudi
--

ALTER SEQUENCE tb_raint_id_seq1 OWNED BY tb_raint.id;


--
-- TOC entry 235 (class 1259 OID 17399)
-- Name: tb_recomendacao; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_recomendacao (
    id bigint NOT NULL,
    numero_recomendacao smallint,
    item_fk bigint NOT NULL,
    unidade_administrativa_fk bigint NOT NULL,
    recomendacao_tipo_fk smallint NOT NULL,
    recomendacao_gravidade_fk smallint,
    recomendacao_categoria_fk bigint,
    recomendacao_subcategoria_fk bigint,
    descricao_recomendacao text,
    data_gravacao date
);


ALTER TABLE siaudi.tb_recomendacao OWNER TO usrsiaudi;

--
-- TOC entry 3011 (class 0 OID 0)
-- Dependencies: 235
-- Name: TABLE tb_recomendacao; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_recomendacao IS 'Tabela de recomendações dos relatórios do módulo Relatoria.';


--
-- TOC entry 236 (class 1259 OID 17405)
-- Name: tb_recomendacao_categoria_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_recomendacao_categoria_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_recomendacao_categoria_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 237 (class 1259 OID 17407)
-- Name: tb_recomendacao_categoria; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_recomendacao_categoria (
    id integer DEFAULT nextval('tb_recomendacao_categoria_id_seq'::regclass) NOT NULL,
    nome_categoria character varying(200) NOT NULL
);


ALTER TABLE siaudi.tb_recomendacao_categoria OWNER TO usrsiaudi;

--
-- TOC entry 3014 (class 0 OID 0)
-- Dependencies: 237
-- Name: TABLE tb_recomendacao_categoria; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_recomendacao_categoria IS 'Armazena as categorias da recomendação.';


--
-- TOC entry 238 (class 1259 OID 17411)
-- Name: tb_recomendacao_gravidade_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_recomendacao_gravidade_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_recomendacao_gravidade_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 239 (class 1259 OID 17413)
-- Name: tb_recomendacao_gravidade; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_recomendacao_gravidade (
    id integer DEFAULT nextval('tb_recomendacao_gravidade_id_seq'::regclass) NOT NULL,
    nome_gravidade character varying(100) NOT NULL
);


ALTER TABLE siaudi.tb_recomendacao_gravidade OWNER TO usrsiaudi;

--
-- TOC entry 3017 (class 0 OID 0)
-- Dependencies: 239
-- Name: TABLE tb_recomendacao_gravidade; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_recomendacao_gravidade IS 'Armazena as gravidades da recomendação.';


--
-- TOC entry 240 (class 1259 OID 17417)
-- Name: tb_recomendacao_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_recomendacao_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_recomendacao_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 241 (class 1259 OID 17419)
-- Name: tb_recomendacao_id_seq1; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_recomendacao_id_seq1
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_recomendacao_id_seq1 OWNER TO usrsiaudi;

--
-- TOC entry 3020 (class 0 OID 0)
-- Dependencies: 241
-- Name: tb_recomendacao_id_seq1; Type: SEQUENCE OWNED BY; Schema: siaudi; Owner: usrsiaudi
--

ALTER SEQUENCE tb_recomendacao_id_seq1 OWNED BY tb_recomendacao.id;


--
-- TOC entry 242 (class 1259 OID 17421)
-- Name: tb_recomendacao_padrao_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_recomendacao_padrao_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_recomendacao_padrao_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 243 (class 1259 OID 17423)
-- Name: tb_recomendacao_padrao; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_recomendacao_padrao (
    id bigint DEFAULT nextval('tb_recomendacao_padrao_id_seq'::regclass) NOT NULL,
    recomendacao text NOT NULL
);


ALTER TABLE siaudi.tb_recomendacao_padrao OWNER TO usrsiaudi;

--
-- TOC entry 3023 (class 0 OID 0)
-- Dependencies: 243
-- Name: TABLE tb_recomendacao_padrao; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_recomendacao_padrao IS 'Tabela para a recomendação padrão (pré-cadastro) que pode ser importada e alterada na recomendação do relatório de auditoria.';


--
-- TOC entry 244 (class 1259 OID 17430)
-- Name: tb_recomendacao_padrao_id_seq1; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_recomendacao_padrao_id_seq1
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_recomendacao_padrao_id_seq1 OWNER TO usrsiaudi;

--
-- TOC entry 3025 (class 0 OID 0)
-- Dependencies: 244
-- Name: tb_recomendacao_padrao_id_seq1; Type: SEQUENCE OWNED BY; Schema: siaudi; Owner: usrsiaudi
--

ALTER SEQUENCE tb_recomendacao_padrao_id_seq1 OWNED BY tb_recomendacao_padrao.id;


--
-- TOC entry 245 (class 1259 OID 17432)
-- Name: tb_recomendacao_subcategoria_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_recomendacao_subcategoria_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_recomendacao_subcategoria_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 246 (class 1259 OID 17434)
-- Name: tb_recomendacao_subcategoria; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_recomendacao_subcategoria (
    id integer DEFAULT nextval('tb_recomendacao_subcategoria_id_seq'::regclass) NOT NULL,
    recomendacao_categoria_fk integer NOT NULL,
    nome_subcategoria character varying(200) NOT NULL
);


ALTER TABLE siaudi.tb_recomendacao_subcategoria OWNER TO usrsiaudi;

--
-- TOC entry 3028 (class 0 OID 0)
-- Dependencies: 246
-- Name: TABLE tb_recomendacao_subcategoria; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_recomendacao_subcategoria IS 'Armazena as subcategorias da recomendação.';


--
-- TOC entry 247 (class 1259 OID 17438)
-- Name: tb_recomendacao_tipo_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_recomendacao_tipo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_recomendacao_tipo_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 248 (class 1259 OID 17440)
-- Name: tb_recomendacao_tipo; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_recomendacao_tipo (
    id bigint DEFAULT nextval('tb_recomendacao_tipo_id_seq'::regclass) NOT NULL,
    nome_tipo character varying(200)
);


ALTER TABLE siaudi.tb_recomendacao_tipo OWNER TO usrsiaudi;

--
-- TOC entry 3031 (class 0 OID 0)
-- Dependencies: 248
-- Name: TABLE tb_recomendacao_tipo; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_recomendacao_tipo IS 'Armazena os tipos da recomendação (tabela de apoio).';


--
-- TOC entry 249 (class 1259 OID 17444)
-- Name: tb_relatorio; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_relatorio (
    id bigint NOT NULL,
    numero_relatorio smallint,
    data_relatorio date,
    especie_auditoria_fk smallint NOT NULL,
    descricao_introducao text,
    categoria_fk smallint NOT NULL,
    data_finalizado date,
    valor_prazo smallint,
    st_libera_homologa smallint,
    data_gravacao date,
    data_regulariza date,
    login_finaliza character varying(60),
    login_homologa character varying(60),
    login_relatorio character varying(80),
    data_pre_finalizado date,
    nucleo boolean,
    login_pre_finaliza character varying(60),
    plan_especifico_fk integer
);


ALTER TABLE siaudi.tb_relatorio OWNER TO usrsiaudi;

--
-- TOC entry 3033 (class 0 OID 0)
-- Dependencies: 249
-- Name: TABLE tb_relatorio; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_relatorio IS 'Tabela de relatórios do módulo Relatoria.';


--
-- TOC entry 250 (class 1259 OID 17450)
-- Name: tb_relatorio_acesso; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_relatorio_acesso (
    relatorio_fk bigint NOT NULL,
    nome_login character varying(50) NOT NULL,
    unidade_administrativa_fk bigint
);


ALTER TABLE siaudi.tb_relatorio_acesso OWNER TO usrsiaudi;

--
-- TOC entry 3035 (class 0 OID 0)
-- Dependencies: 250
-- Name: TABLE tb_relatorio_acesso; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_relatorio_acesso IS 'Define qual auditor tem acesso a qual relatório (e superintendência)';


--
-- TOC entry 251 (class 1259 OID 17453)
-- Name: tb_relatorio_acesso_item_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_relatorio_acesso_item_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_relatorio_acesso_item_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 252 (class 1259 OID 17455)
-- Name: tb_relatorio_acesso_item; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_relatorio_acesso_item (
    id bigint DEFAULT nextval('tb_relatorio_acesso_item_id_seq'::regclass) NOT NULL,
    item_fk bigint NOT NULL,
    nome_login character varying(60) NOT NULL,
    unidade_administrativa_fk bigint NOT NULL,
    nome_login_cliente character varying(60) NOT NULL,
    data_liberacao timestamp without time zone DEFAULT now() NOT NULL
);


ALTER TABLE siaudi.tb_relatorio_acesso_item OWNER TO usrsiaudi;

--
-- TOC entry 3038 (class 0 OID 0)
-- Dependencies: 252
-- Name: TABLE tb_relatorio_acesso_item; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_relatorio_acesso_item IS 'Armazena quais itens os auditores tem acesso.';


--
-- TOC entry 253 (class 1259 OID 17460)
-- Name: tb_relatorio_area; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_relatorio_area (
    relatorio_fk bigint NOT NULL,
    unidade_administrativa_fk integer NOT NULL
);


ALTER TABLE siaudi.tb_relatorio_area OWNER TO usrsiaudi;

--
-- TOC entry 3040 (class 0 OID 0)
-- Dependencies: 253
-- Name: TABLE tb_relatorio_area; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_relatorio_area IS 'Tabela auxiliar para receber as areas por relatório.';


--
-- TOC entry 254 (class 1259 OID 17463)
-- Name: tb_relatorio_auditor; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_relatorio_auditor (
    relatorio_fk bigint NOT NULL,
    usuario_fk integer
);


ALTER TABLE siaudi.tb_relatorio_auditor OWNER TO usrsiaudi;

--
-- TOC entry 255 (class 1259 OID 17466)
-- Name: tb_relatorio_cabecalho_rodape_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_relatorio_cabecalho_rodape_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_relatorio_cabecalho_rodape_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 256 (class 1259 OID 17468)
-- Name: tb_relatorio_despacho; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_relatorio_despacho (
    id integer DEFAULT nextval('tb_relatorio_cabecalho_rodape_id_seq'::regclass) NOT NULL,
    descricao_finalizado character varying(2000),
    descricao_homologado character varying(2000),
    descricao_pre_finalizado character varying(2000)
);


ALTER TABLE siaudi.tb_relatorio_despacho OWNER TO usrsiaudi;

--
-- TOC entry 257 (class 1259 OID 17475)
-- Name: tb_relatorio_diretoria; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_relatorio_diretoria (
    relatorio_fk bigint NOT NULL,
    diretoria_fk bigint
);


ALTER TABLE siaudi.tb_relatorio_diretoria OWNER TO usrsiaudi;

--
-- TOC entry 3045 (class 0 OID 0)
-- Dependencies: 257
-- Name: TABLE tb_relatorio_diretoria; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_relatorio_diretoria IS 'Tabela auxiliar para receber os tipos de diretoria por relatório.';


--
-- TOC entry 258 (class 1259 OID 17478)
-- Name: tb_relatorio_gerente; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_relatorio_gerente (
    relatorio_fk bigint NOT NULL,
    usuario_fk bigint
);


ALTER TABLE siaudi.tb_relatorio_gerente OWNER TO usrsiaudi;

--
-- TOC entry 259 (class 1259 OID 17481)
-- Name: tb_relatorio_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_relatorio_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_relatorio_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 3048 (class 0 OID 0)
-- Dependencies: 259
-- Name: tb_relatorio_id_seq; Type: SEQUENCE OWNED BY; Schema: siaudi; Owner: usrsiaudi
--

ALTER SEQUENCE tb_relatorio_id_seq OWNED BY tb_relatorio.id;


--
-- TOC entry 260 (class 1259 OID 17483)
-- Name: tb_relatorio_reiniciar; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_relatorio_reiniciar (
    st_reiniciar boolean,
    data date,
    login character varying(60)
);


ALTER TABLE siaudi.tb_relatorio_reiniciar OWNER TO usrsiaudi;

--
-- TOC entry 3050 (class 0 OID 0)
-- Dependencies: 260
-- Name: TABLE tb_relatorio_reiniciar; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_relatorio_reiniciar IS 'Armazena as datas e login de usuário quando o reinício da contagem de itens do relatório for solicitado.';


--
-- TOC entry 261 (class 1259 OID 17486)
-- Name: tb_relatorio_risco_pos; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_relatorio_risco_pos (
    risco_pos_fk bigint NOT NULL,
    relatorio_fk bigint
);


ALTER TABLE siaudi.tb_relatorio_risco_pos OWNER TO usrsiaudi;

--
-- TOC entry 3052 (class 0 OID 0)
-- Dependencies: 261
-- Name: TABLE tb_relatorio_risco_pos; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_relatorio_risco_pos IS 'Armazena os riscos pós-identificados de cada relatório.';


--
-- TOC entry 262 (class 1259 OID 17489)
-- Name: tb_relatorio_setor; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_relatorio_setor (
    relatorio_fk bigint NOT NULL,
    unidade_administrativa_fk integer NOT NULL
);


ALTER TABLE siaudi.tb_relatorio_setor OWNER TO usrsiaudi;

--
-- TOC entry 3054 (class 0 OID 0)
-- Dependencies: 262
-- Name: TABLE tb_relatorio_setor; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_relatorio_setor IS 'Tabela auxiliar para receber os setores por relatório.';


--
-- TOC entry 263 (class 1259 OID 17492)
-- Name: tb_relatorio_sureg; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_relatorio_sureg (
    relatorio_fk bigint NOT NULL,
    unidade_administrativa_fk bigint NOT NULL,
    sureg_secundaria boolean
);


ALTER TABLE siaudi.tb_relatorio_sureg OWNER TO usrsiaudi;

--
-- TOC entry 3056 (class 0 OID 0)
-- Dependencies: 263
-- Name: TABLE tb_relatorio_sureg; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_relatorio_sureg IS 'Superintendências auditadas vinculadas ao relatório.';


--
-- TOC entry 264 (class 1259 OID 17495)
-- Name: tb_resposta; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_resposta (
    id bigint NOT NULL,
    tipo_status_fk smallint,
    recomendacao_fk bigint NOT NULL,
    data_resposta date NOT NULL,
    id_usuario_log character varying(60) NOT NULL,
    descricao_resposta text NOT NULL,
    tipo_arquivo character varying(5),
    nome_arquivo character varying(30),
    conteudo_arquivo text
);


ALTER TABLE siaudi.tb_resposta OWNER TO usrsiaudi;

--
-- TOC entry 3058 (class 0 OID 0)
-- Dependencies: 264
-- Name: TABLE tb_resposta; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_resposta IS 'Armazena a resposta da recomendação.';


--
-- TOC entry 265 (class 1259 OID 17501)
-- Name: tb_resposta_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_resposta_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_resposta_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 3060 (class 0 OID 0)
-- Dependencies: 265
-- Name: tb_resposta_id_seq; Type: SEQUENCE OWNED BY; Schema: siaudi; Owner: usrsiaudi
--

ALTER SEQUENCE tb_resposta_id_seq OWNED BY tb_resposta.id;


--
-- TOC entry 298 (class 1259 OID 22017)
-- Name: tb_restricao_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_restricao_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_restricao_seq OWNER TO usrsiaudi;

--
-- TOC entry 299 (class 1259 OID 22019)
-- Name: tb_restricao; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_restricao (
    id bigint DEFAULT nextval('tb_restricao_seq'::regclass) NOT NULL,
    nome_componente character varying(50) NOT NULL,
    descricao_componente character varying(250),
    nome_propriedade character varying(50),
    valor_propriedade character varying(50)
);


ALTER TABLE siaudi.tb_restricao OWNER TO usrsiaudi;

--
-- TOC entry 3062 (class 0 OID 0)
-- Dependencies: 299
-- Name: TABLE tb_restricao; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_restricao IS 'Entidade que armazena os controles/componentes cujo acesso sofrera restricao.';


--
-- TOC entry 3063 (class 0 OID 0)
-- Dependencies: 299
-- Name: COLUMN tb_restricao.id; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON COLUMN tb_restricao.id IS 'Chave Primaria da tabela (PK)';


--
-- TOC entry 3064 (class 0 OID 0)
-- Dependencies: 299
-- Name: COLUMN tb_restricao.nome_componente; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON COLUMN tb_restricao.nome_componente IS 'Nome do controle, componente, wideget, gadget, tag';


--
-- TOC entry 3065 (class 0 OID 0)
-- Dependencies: 299
-- Name: COLUMN tb_restricao.descricao_componente; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON COLUMN tb_restricao.descricao_componente IS 'Descricao do controle, componente, wideget, gadget, tag';


--
-- TOC entry 3066 (class 0 OID 0)
-- Dependencies: 299
-- Name: COLUMN tb_restricao.nome_propriedade; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON COLUMN tb_restricao.nome_propriedade IS 'Nome da propriedade que sera a restricao imposta';


--
-- TOC entry 3067 (class 0 OID 0)
-- Dependencies: 299
-- Name: COLUMN tb_restricao.valor_propriedade; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON COLUMN tb_restricao.valor_propriedade IS 'Valor da propriedade que sera a restricao imposta';


--
-- TOC entry 300 (class 1259 OID 22023)
-- Name: tb_restricao_modulo_perfil; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_restricao_modulo_perfil (
    perfil_fk bigint NOT NULL,
    modulo_fk bigint NOT NULL,
    restricao_fk bigint NOT NULL
);


ALTER TABLE siaudi.tb_restricao_modulo_perfil OWNER TO usrsiaudi;

--
-- TOC entry 266 (class 1259 OID 17503)
-- Name: tb_risco_pos; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_risco_pos (
    id bigint NOT NULL,
    descricao_impacto text,
    nome_risco text NOT NULL,
    descricao_mitigacao text
);


ALTER TABLE siaudi.tb_risco_pos OWNER TO usrsiaudi;

--
-- TOC entry 3070 (class 0 OID 0)
-- Dependencies: 266
-- Name: TABLE tb_risco_pos; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_risco_pos IS 'Armazena o cadastro dos riscos pós-identificados.';


--
-- TOC entry 267 (class 1259 OID 17509)
-- Name: tb_risco_pos_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_risco_pos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_risco_pos_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 3072 (class 0 OID 0)
-- Dependencies: 267
-- Name: tb_risco_pos_id_seq; Type: SEQUENCE OWNED BY; Schema: siaudi; Owner: usrsiaudi
--

ALTER SEQUENCE tb_risco_pos_id_seq OWNED BY tb_risco_pos.id;


--
-- TOC entry 268 (class 1259 OID 17511)
-- Name: tb_risco_pre; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_risco_pre (
    id bigint NOT NULL,
    descricao_impacto text,
    nome_risco text NOT NULL,
    descricao_mitigacao text
);


ALTER TABLE siaudi.tb_risco_pre OWNER TO usrsiaudi;

--
-- TOC entry 3074 (class 0 OID 0)
-- Dependencies: 268
-- Name: TABLE tb_risco_pre; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_risco_pre IS 'Armazena o cadastro dos riscos pré-identificados.';


--
-- TOC entry 269 (class 1259 OID 17517)
-- Name: tb_risco_pre_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_risco_pre_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_risco_pre_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 3076 (class 0 OID 0)
-- Dependencies: 269
-- Name: tb_risco_pre_id_seq; Type: SEQUENCE OWNED BY; Schema: siaudi; Owner: usrsiaudi
--

ALTER SEQUENCE tb_risco_pre_id_seq OWNED BY tb_risco_pre.id;


--
-- TOC entry 301 (class 1259 OID 22026)
-- Name: tb_sistema; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_sistema (
    id bigint NOT NULL,
    nome character varying(1024) NOT NULL,
    url character varying(1024) NOT NULL,
    descricao character varying(1024) NOT NULL,
    icone character varying(1024) NOT NULL,
    ativo boolean DEFAULT true NOT NULL
);


ALTER TABLE siaudi.tb_sistema OWNER TO usrsiaudi;

--
-- TOC entry 270 (class 1259 OID 17519)
-- Name: tb_subcriterio; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_subcriterio (
    id integer NOT NULL,
    criterio_fk bigint NOT NULL,
    nome_criterio character varying(200) NOT NULL,
    valor_peso smallint NOT NULL
);


ALTER TABLE siaudi.tb_subcriterio OWNER TO usrsiaudi;

--
-- TOC entry 271 (class 1259 OID 17522)
-- Name: tb_subcriterio_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_subcriterio_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_subcriterio_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 3080 (class 0 OID 0)
-- Dependencies: 271
-- Name: tb_subcriterio_id_seq; Type: SEQUENCE OWNED BY; Schema: siaudi; Owner: usrsiaudi
--

ALTER SEQUENCE tb_subcriterio_id_seq OWNED BY tb_subcriterio.id;


--
-- TOC entry 272 (class 1259 OID 17524)
-- Name: tb_subrisco; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_subrisco (
    id bigint NOT NULL,
    subcriterio_fk integer NOT NULL,
    processo_fk integer NOT NULL,
    numero_nota numeric NOT NULL,
    data_log timestamp without time zone NOT NULL,
    id_usuario bigint NOT NULL
);


ALTER TABLE siaudi.tb_subrisco OWNER TO usrsiaudi;

--
-- TOC entry 273 (class 1259 OID 17530)
-- Name: tb_subrisco_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_subrisco_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_subrisco_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 3083 (class 0 OID 0)
-- Dependencies: 273
-- Name: tb_subrisco_id_seq; Type: SEQUENCE OWNED BY; Schema: siaudi; Owner: usrsiaudi
--

ALTER SEQUENCE tb_subrisco_id_seq OWNED BY tb_subrisco.id;


--
-- TOC entry 274 (class 1259 OID 17532)
-- Name: tb_substituto_regional; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_substituto_regional (
    id bigint NOT NULL,
    valor_matricula_titular integer NOT NULL,
    nome_titular character varying(60) NOT NULL,
    valor_logintitular character varying(40) NOT NULL,
    nome_local bigint NOT NULL,
    valor_matricula_substituto integer NOT NULL,
    nome_substituto character varying(60) NOT NULL,
    nome_login_substituto character varying(40) NOT NULL,
    valor_filial smallint
);


ALTER TABLE siaudi.tb_substituto_regional OWNER TO usrsiaudi;

--
-- TOC entry 3085 (class 0 OID 0)
-- Dependencies: 274
-- Name: TABLE tb_substituto_regional; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_substituto_regional IS 'Tabela para armazenar o substituto da superintendência.';


--
-- TOC entry 275 (class 1259 OID 17535)
-- Name: tb_substituto_regional_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_substituto_regional_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_substituto_regional_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 3087 (class 0 OID 0)
-- Dependencies: 275
-- Name: tb_substituto_regional_id_seq; Type: SEQUENCE OWNED BY; Schema: siaudi; Owner: usrsiaudi
--

ALTER SEQUENCE tb_substituto_regional_id_seq OWNED BY tb_substituto_regional.id;


SET default_with_oids = true;

--
-- TOC entry 276 (class 1259 OID 17537)
-- Name: tb_sureg; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_sureg (
    id bigint NOT NULL,
    nome character varying(80) NOT NULL,
    sigla character varying(20) NOT NULL,
    uf_fk character varying(2) NOT NULL,
    subordinante_fk bigint
);


ALTER TABLE siaudi.tb_sureg OWNER TO usrsiaudi;

SET default_with_oids = false;

--
-- TOC entry 277 (class 1259 OID 17540)
-- Name: tb_tipo_cliente; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_tipo_cliente (
    id smallint NOT NULL,
    descricao_tipo_cliente character varying(50) NOT NULL
);


ALTER TABLE siaudi.tb_tipo_cliente OWNER TO usrsiaudi;

--
-- TOC entry 3090 (class 0 OID 0)
-- Dependencies: 277
-- Name: TABLE tb_tipo_cliente; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_tipo_cliente IS 'Tabela que armazena os tipos de cliente.';


--
-- TOC entry 278 (class 1259 OID 17543)
-- Name: tb_tipo_cliente_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_tipo_cliente_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_tipo_cliente_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 3092 (class 0 OID 0)
-- Dependencies: 278
-- Name: tb_tipo_cliente_id_seq; Type: SEQUENCE OWNED BY; Schema: siaudi; Owner: usrsiaudi
--

ALTER SEQUENCE tb_tipo_cliente_id_seq OWNED BY tb_tipo_cliente.id;


--
-- TOC entry 279 (class 1259 OID 17545)
-- Name: tb_tipo_criterio; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_tipo_criterio (
    id bigint NOT NULL,
    nome_criterio character varying(100) NOT NULL
);


ALTER TABLE siaudi.tb_tipo_criterio OWNER TO usrsiaudi;

--
-- TOC entry 3094 (class 0 OID 0)
-- Dependencies: 279
-- Name: TABLE tb_tipo_criterio; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_tipo_criterio IS 'Tabela auxiliar para os critérios: criticidade, materialidade e relevância estratégica.';


--
-- TOC entry 280 (class 1259 OID 17548)
-- Name: tb_tipo_criterio_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_tipo_criterio_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_tipo_criterio_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 3096 (class 0 OID 0)
-- Dependencies: 280
-- Name: tb_tipo_criterio_id_seq; Type: SEQUENCE OWNED BY; Schema: siaudi; Owner: usrsiaudi
--

ALTER SEQUENCE tb_tipo_criterio_id_seq OWNED BY tb_tipo_criterio.id;


--
-- TOC entry 281 (class 1259 OID 17550)
-- Name: tb_tipo_diretoria_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_tipo_diretoria_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_tipo_diretoria_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 3098 (class 0 OID 0)
-- Dependencies: 281
-- Name: tb_tipo_diretoria_id_seq; Type: SEQUENCE OWNED BY; Schema: siaudi; Owner: usrsiaudi
--

ALTER SEQUENCE tb_tipo_diretoria_id_seq OWNED BY tb_diretoria.id;


--
-- TOC entry 282 (class 1259 OID 17552)
-- Name: tb_tipo_processo; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_tipo_processo (
    id integer NOT NULL,
    nome_tipo_processo character varying(100) NOT NULL
);


ALTER TABLE siaudi.tb_tipo_processo OWNER TO usrsiaudi;

--
-- TOC entry 3100 (class 0 OID 0)
-- Dependencies: 282
-- Name: TABLE tb_tipo_processo; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_tipo_processo IS 'Tabela auxiliar para tipo da ação. Ex: Operacional ou administrativo.';


--
-- TOC entry 283 (class 1259 OID 17555)
-- Name: tb_tipo_processo_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_tipo_processo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_tipo_processo_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 3102 (class 0 OID 0)
-- Dependencies: 283
-- Name: tb_tipo_processo_id_seq; Type: SEQUENCE OWNED BY; Schema: siaudi; Owner: usrsiaudi
--

ALTER SEQUENCE tb_tipo_processo_id_seq OWNED BY tb_tipo_processo.id;


--
-- TOC entry 284 (class 1259 OID 17557)
-- Name: tb_tipo_status; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_tipo_status (
    id integer NOT NULL,
    descricao_status character varying(25) NOT NULL
);


ALTER TABLE siaudi.tb_tipo_status OWNER TO usrsiaudi;

--
-- TOC entry 3104 (class 0 OID 0)
-- Dependencies: 284
-- Name: TABLE tb_tipo_status; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_tipo_status IS 'Tabela auxiliar com os tipos de status possíveis.';


--
-- TOC entry 285 (class 1259 OID 17560)
-- Name: tb_tipo_status_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_tipo_status_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_tipo_status_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 3106 (class 0 OID 0)
-- Dependencies: 285
-- Name: tb_tipo_status_id_seq; Type: SEQUENCE OWNED BY; Schema: siaudi; Owner: usrsiaudi
--

ALTER SEQUENCE tb_tipo_status_id_seq OWNED BY tb_tipo_status.id;


--
-- TOC entry 294 (class 1259 OID 22005)
-- Name: tb_uf; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_uf (
    sigla character varying(2) NOT NULL,
    nome character varying(43) NOT NULL,
    uf character varying(2)
);


ALTER TABLE siaudi.tb_uf OWNER TO usrsiaudi;

SET default_with_oids = true;

--
-- TOC entry 286 (class 1259 OID 17562)
-- Name: tb_unidade_administrativa; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_unidade_administrativa (
    id bigint NOT NULL,
    nome character varying(80) NOT NULL,
    sigla character varying(20) NOT NULL,
    uf_fk character varying(2) NOT NULL,
    subordinante_fk bigint,
    sureg boolean DEFAULT false,
    diretoria boolean DEFAULT false,
    data_extincao date
);


ALTER TABLE siaudi.tb_unidade_administrativa OWNER TO usrsiaudi;

--
-- TOC entry 287 (class 1259 OID 17567)
-- Name: tb_unidade_administrativa_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_unidade_administrativa_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_unidade_administrativa_id_seq OWNER TO usrsiaudi;

--
-- TOC entry 3110 (class 0 OID 0)
-- Dependencies: 287
-- Name: tb_unidade_administrativa_id_seq; Type: SEQUENCE OWNED BY; Schema: siaudi; Owner: usrsiaudi
--

ALTER SEQUENCE tb_unidade_administrativa_id_seq OWNED BY tb_unidade_administrativa.id;


--
-- TOC entry 288 (class 1259 OID 17569)
-- Name: tb_usuario_id_seq; Type: SEQUENCE; Schema: siaudi; Owner: usrsiaudi
--

CREATE SEQUENCE tb_usuario_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE siaudi.tb_usuario_id_seq OWNER TO usrsiaudi;

SET default_with_oids = false;

--
-- TOC entry 289 (class 1259 OID 17571)
-- Name: tb_usuario; Type: TABLE; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE TABLE tb_usuario (
    nome_login character varying(60) NOT NULL,
    id bigint DEFAULT nextval('tb_usuario_id_seq'::regclass) NOT NULL,
    nome_usuario character varying(400),
    perfil_fk bigint NOT NULL,
    nucleo_fk bigint NOT NULL,
    unidade_administrativa_fk bigint,
    cargo_fk bigint,
    substituto_fk bigint,
    funcao_fk bigint,
    cpf character varying(11),
    email character varying(100),
    senha character varying(255)
);


ALTER TABLE siaudi.tb_usuario OWNER TO usrsiaudi;

--
-- TOC entry 3113 (class 0 OID 0)
-- Dependencies: 289
-- Name: TABLE tb_usuario; Type: COMMENT; Schema: siaudi; Owner: usrsiaudi
--

COMMENT ON TABLE tb_usuario IS 'Armazena os usuários vinculados ao sistema.';


--
-- TOC entry 302 (class 1259 OID 22123)
-- Name: vw_menu; Type: VIEW; Schema: siaudi; Owner: usrsiaudi
--

CREATE VIEW vw_menu AS
 SELECT stm.id AS id_sistema,
    stm.nome AS nome_sistema,
    mn.id,
    mn.menu_pai_fk,
    mp.perfil_fk,
    mn.nivel,
    mn.ordem,
    mn.coluna,
    mn.titulo,
    mn.descricao,
    mn.url,
    mn.imagem,
    mn.url_parametro
   FROM (((tb_menu mn
     LEFT JOIN tb_menu_perfil mp ON ((mn.id = mp.menu_fk)))
     LEFT JOIN tb_perfil pe ON ((mp.perfil_fk = pe.id)))
     JOIN tb_sistema stm ON ((mn.sistema_fk = stm.id)));


ALTER TABLE siaudi.vw_menu OWNER TO usrsiaudi;

--
-- TOC entry 303 (class 1259 OID 22128)
-- Name: vw_perfil; Type: VIEW; Schema: siaudi; Owner: usrsiaudi
--

CREATE VIEW vw_perfil AS
 SELECT tb_perfil.id,
    tb_perfil.nome_interno AS nome,
    tb_perfil.nome_perfil AS descricao,
    tb_perfil.sistema_fk
   FROM tb_perfil;


ALTER TABLE siaudi.vw_perfil OWNER TO usrsiaudi;

--
-- TOC entry 304 (class 1259 OID 22132)
-- Name: vw_restricao; Type: VIEW; Schema: siaudi; Owner: usrsiaudi
--

CREATE VIEW vw_restricao AS
 SELECT stm.id AS id_sistema,
    stm.nome AS nome_sistema,
    prf.id AS id_perfil,
    prf.nome_interno AS nome_perfil,
    prf.nome_perfil AS descricao_perfil,
    md.id AS id_modulo,
    md.nome AS nome_modulo,
    md.descricao AS descricao_modulo,
    rst.id AS id_restricao,
    rst.nome_componente,
    rst.descricao_componente,
    rst.nome_propriedade,
    rst.valor_propriedade
   FROM ((((tb_restricao_modulo_perfil rmp
     JOIN tb_perfil prf ON ((rmp.perfil_fk = prf.id)))
     JOIN tb_sistema stm ON ((prf.sistema_fk = stm.id)))
     JOIN tb_modulo md ON ((rmp.modulo_fk = md.id)))
     JOIN tb_restricao rst ON ((rmp.restricao_fk = rst.id)));


ALTER TABLE siaudi.vw_restricao OWNER TO usrsiaudi;

--
-- TOC entry 305 (class 1259 OID 22138)
-- Name: vw_sistema; Type: VIEW; Schema: siaudi; Owner: usrsiaudi
--

CREATE VIEW vw_sistema AS
 SELECT tb_sistema.id,
    tb_sistema.nome,
    tb_sistema.url,
    tb_sistema.descricao,
    tb_sistema.icone
   FROM tb_sistema;


ALTER TABLE siaudi.vw_sistema OWNER TO usrsiaudi;

--
-- TOC entry 290 (class 1259 OID 17575)
-- Name: vw_sureg; Type: VIEW; Schema: siaudi; Owner: usrsiaudi
--

CREATE VIEW vw_sureg AS
 SELECT tb_unidade_administrativa.id,
    tb_unidade_administrativa.nome,
    tb_unidade_administrativa.sigla,
    tb_unidade_administrativa.uf_fk,
    tb_unidade_administrativa.subordinante_fk
   FROM tb_unidade_administrativa
  WHERE (tb_unidade_administrativa.id = ANY (ARRAY[(259)::bigint, (290)::bigint, (108)::bigint, (214)::bigint, (204)::bigint, (85)::bigint, (156)::bigint, (249)::bigint, (117)::bigint, (389)::bigint, (161)::bigint, (1070)::bigint, (3763)::bigint, (436)::bigint, (410)::bigint, (157)::bigint, (200)::bigint, (312)::bigint, (1319)::bigint, (272)::bigint, (1581)::bigint, (166)::bigint, (408)::bigint, (175)::bigint, (247)::bigint, (148)::bigint, (345)::bigint, (92)::bigint, (429)::bigint, (263)::bigint, (438)::bigint, (341)::bigint, (128)::bigint, (3577)::bigint, (1024)::bigint, (1300)::bigint, (3582)::bigint, (3799)::bigint, (3812)::bigint, (1444)::bigint, (410)::bigint]))
  ORDER BY tb_unidade_administrativa.nome;


ALTER TABLE siaudi.vw_sureg OWNER TO usrsiaudi;

--
-- TOC entry 306 (class 1259 OID 22142)
-- Name: vw_usuario; Type: VIEW; Schema: siaudi; Owner: usrsiaudi
--

CREATE VIEW vw_usuario AS
 SELECT u.id AS id_usuario,
    u.nome_login AS login,
    fg.nome_funcao AS funcao,
    u.nome_usuario,
    u.email,
    u.cpf,
    u.senha,
    ua.id AS id_und_adm,
    ua.nome AS nome_und_adm,
    ua.sigla AS sigla_und_adm,
    ua.uf_fk AS uf_und_adm
   FROM ((tb_usuario u
     JOIN tb_unidade_administrativa ua ON ((u.unidade_administrativa_fk = ua.id)))
     LEFT JOIN tb_funcao fg ON ((u.funcao_fk = fg.id)));


ALTER TABLE siaudi.vw_usuario OWNER TO usrsiaudi;

--
-- TOC entry 307 (class 1259 OID 22147)
-- Name: vw_usuario_perfil; Type: VIEW; Schema: siaudi; Owner: usrsiaudi
--

CREATE VIEW vw_usuario_perfil AS
 SELECT tb_usuario.id,
    tb_usuario.id AS usuario_fk,
    tb_usuario.perfil_fk
   FROM tb_usuario;


ALTER TABLE siaudi.vw_usuario_perfil OWNER TO usrsiaudi;

--
-- TOC entry 308 (class 1259 OID 22151)
-- Name: vw_usuario_perfil_sistema; Type: VIEW; Schema: siaudi; Owner: usrsiaudi
--

CREATE VIEW vw_usuario_perfil_sistema AS
 SELECT usr.id_usuario,
    prf.id AS id_perfil,
    sis.id AS id_sistema
   FROM (((vw_usuario usr
     JOIN vw_usuario_perfil usrprf ON ((usr.id_usuario = usrprf.usuario_fk)))
     JOIN vw_perfil prf ON ((usrprf.perfil_fk = prf.id)))
     JOIN vw_sistema sis ON ((prf.sistema_fk = sis.id)));


ALTER TABLE siaudi.vw_usuario_perfil_sistema OWNER TO usrsiaudi;

--
-- TOC entry 2398 (class 2604 OID 17580)
-- Name: id; Type: DEFAULT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_acao ALTER COLUMN id SET DEFAULT nextval('tb_acao_id_seq'::regclass);


--
-- TOC entry 2400 (class 2604 OID 17581)
-- Name: relatorio_fk; Type: DEFAULT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_avaliacao ALTER COLUMN relatorio_fk SET DEFAULT nextval('tb_avaliacao_relatorio_fk_seq'::regclass);


--
-- TOC entry 2401 (class 2604 OID 17582)
-- Name: unidade_administrativa_fk; Type: DEFAULT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_avaliacao ALTER COLUMN unidade_administrativa_fk SET DEFAULT nextval('tb_avaliacao_sureg_fk_seq'::regclass);


--
-- TOC entry 2402 (class 2604 OID 17583)
-- Name: usuario_fk; Type: DEFAULT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_avaliacao ALTER COLUMN usuario_fk SET DEFAULT nextval('tb_avaliacao_auditor_fk_seq'::regclass);


--
-- TOC entry 2407 (class 2604 OID 22121)
-- Name: id; Type: DEFAULT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_cargo ALTER COLUMN id SET DEFAULT nextval('tb_cargo_id_seq'::regclass);


--
-- TOC entry 2408 (class 2604 OID 17585)
-- Name: id; Type: DEFAULT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_categoria ALTER COLUMN id SET DEFAULT nextval('tb_categoria_id_seq'::regclass);


--
-- TOC entry 2409 (class 2604 OID 17586)
-- Name: id; Type: DEFAULT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_criterio ALTER COLUMN id SET DEFAULT nextval('tb_criterio_id_seq'::regclass);


--
-- TOC entry 2410 (class 2604 OID 17587)
-- Name: id; Type: DEFAULT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_diretoria ALTER COLUMN id SET DEFAULT nextval('tb_tipo_diretoria_id_seq'::regclass);


--
-- TOC entry 2411 (class 2604 OID 17588)
-- Name: id; Type: DEFAULT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_especie_auditoria ALTER COLUMN id SET DEFAULT nextval('tb_especie_auditoria_id_seq'::regclass);


--
-- TOC entry 2412 (class 2604 OID 17589)
-- Name: id; Type: DEFAULT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_feriado ALTER COLUMN id SET DEFAULT nextval('tb_feriado2_id_seq'::regclass);


--
-- TOC entry 2413 (class 2604 OID 17590)
-- Name: id; Type: DEFAULT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_funcao ALTER COLUMN id SET DEFAULT nextval('tb_funcao_id_seq'::regclass);


--
-- TOC entry 2414 (class 2604 OID 17591)
-- Name: id; Type: DEFAULT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_homens_hora ALTER COLUMN id SET DEFAULT nextval('tb_homens_hora_id_seq'::regclass);


--
-- TOC entry 2416 (class 2604 OID 17592)
-- Name: id; Type: DEFAULT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_imagem ALTER COLUMN id SET DEFAULT nextval('tb_imagem_id_seq'::regclass);


--
-- TOC entry 2418 (class 2604 OID 17593)
-- Name: id; Type: DEFAULT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_item ALTER COLUMN id SET DEFAULT nextval('tb_item_id_seq'::regclass);


--
-- TOC entry 2419 (class 2604 OID 17594)
-- Name: id; Type: DEFAULT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_log_entrada ALTER COLUMN id SET DEFAULT nextval('tb_log_entrada_id_seq1'::regclass);


--
-- TOC entry 2420 (class 2604 OID 17595)
-- Name: id; Type: DEFAULT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_manifestacao ALTER COLUMN id SET DEFAULT nextval('tb_manifestacao_id_seq'::regclass);


--
-- TOC entry 2423 (class 2604 OID 17596)
-- Name: id; Type: DEFAULT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_paint ALTER COLUMN id SET DEFAULT nextval('tb_paint_id_seq'::regclass);


--
-- TOC entry 2427 (class 2604 OID 17597)
-- Name: id; Type: DEFAULT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_plan_especifico ALTER COLUMN id SET DEFAULT nextval('tb_plan_especifico_id_seq'::regclass);


--
-- TOC entry 2430 (class 2604 OID 17598)
-- Name: id; Type: DEFAULT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_recomendacao ALTER COLUMN id SET DEFAULT nextval('tb_recomendacao_id_seq1'::regclass);


--
-- TOC entry 2436 (class 2604 OID 17599)
-- Name: id; Type: DEFAULT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_relatorio ALTER COLUMN id SET DEFAULT nextval('tb_relatorio_id_seq'::regclass);


--
-- TOC entry 2440 (class 2604 OID 17600)
-- Name: id; Type: DEFAULT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_resposta ALTER COLUMN id SET DEFAULT nextval('tb_resposta_id_seq'::regclass);


--
-- TOC entry 2441 (class 2604 OID 17601)
-- Name: id; Type: DEFAULT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_risco_pos ALTER COLUMN id SET DEFAULT nextval('tb_risco_pos_id_seq'::regclass);


--
-- TOC entry 2442 (class 2604 OID 17602)
-- Name: id; Type: DEFAULT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_risco_pre ALTER COLUMN id SET DEFAULT nextval('tb_risco_pre_id_seq'::regclass);


--
-- TOC entry 2443 (class 2604 OID 17603)
-- Name: id; Type: DEFAULT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_subcriterio ALTER COLUMN id SET DEFAULT nextval('tb_subcriterio_id_seq'::regclass);


--
-- TOC entry 2444 (class 2604 OID 17604)
-- Name: id; Type: DEFAULT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_subrisco ALTER COLUMN id SET DEFAULT nextval('tb_subrisco_id_seq'::regclass);


--
-- TOC entry 2445 (class 2604 OID 17605)
-- Name: id; Type: DEFAULT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_substituto_regional ALTER COLUMN id SET DEFAULT nextval('tb_substituto_regional_id_seq'::regclass);


--
-- TOC entry 2446 (class 2604 OID 17606)
-- Name: id; Type: DEFAULT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_tipo_cliente ALTER COLUMN id SET DEFAULT nextval('tb_tipo_cliente_id_seq'::regclass);


--
-- TOC entry 2447 (class 2604 OID 17607)
-- Name: id; Type: DEFAULT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_tipo_criterio ALTER COLUMN id SET DEFAULT nextval('tb_tipo_criterio_id_seq'::regclass);


--
-- TOC entry 2448 (class 2604 OID 17608)
-- Name: id; Type: DEFAULT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_tipo_processo ALTER COLUMN id SET DEFAULT nextval('tb_tipo_processo_id_seq'::regclass);


--
-- TOC entry 2449 (class 2604 OID 17609)
-- Name: id; Type: DEFAULT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_tipo_status ALTER COLUMN id SET DEFAULT nextval('tb_tipo_status_id_seq'::regclass);


--
-- TOC entry 2452 (class 2604 OID 17610)
-- Name: id; Type: DEFAULT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_unidade_administrativa ALTER COLUMN id SET DEFAULT nextval('tb_unidade_administrativa_id_seq'::regclass);


--
-- TOC entry 3123 (class 0 OID 0)
-- Dependencies: 171
-- Name: raint_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('raint_id_seq', 1, false);


--
-- TOC entry 2756 (class 0 OID 17200)
-- Dependencies: 172
-- Data for Name: tb_acao; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3124 (class 0 OID 0)
-- Dependencies: 173
-- Name: tb_acao_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_acao_id_seq', 1, false);


--
-- TOC entry 2758 (class 0 OID 17208)
-- Dependencies: 174
-- Data for Name: tb_acao_mes; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 2759 (class 0 OID 17211)
-- Dependencies: 175
-- Data for Name: tb_acao_risco_pre; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 2760 (class 0 OID 17214)
-- Dependencies: 176
-- Data for Name: tb_acao_sureg; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 2762 (class 0 OID 17219)
-- Dependencies: 178
-- Data for Name: tb_avaliacao; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3125 (class 0 OID 0)
-- Dependencies: 179
-- Name: tb_avaliacao_auditor_fk_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_avaliacao_auditor_fk_seq', 1, false);


--
-- TOC entry 2765 (class 0 OID 17227)
-- Dependencies: 181
-- Data for Name: tb_avaliacao_criterio; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3126 (class 0 OID 0)
-- Dependencies: 180
-- Name: tb_avaliacao_criterio_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_avaliacao_criterio_id_seq', 1, false);


--
-- TOC entry 3127 (class 0 OID 0)
-- Dependencies: 177
-- Name: tb_avaliacao_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_avaliacao_id_seq', 1, false);


--
-- TOC entry 2767 (class 0 OID 17233)
-- Dependencies: 183
-- Data for Name: tb_avaliacao_nota; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3128 (class 0 OID 0)
-- Dependencies: 182
-- Name: tb_avaliacao_nota_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_avaliacao_nota_id_seq', 1, false);


--
-- TOC entry 2769 (class 0 OID 17239)
-- Dependencies: 185
-- Data for Name: tb_avaliacao_observacao; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3129 (class 0 OID 0)
-- Dependencies: 184
-- Name: tb_avaliacao_observacao_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_avaliacao_observacao_id_seq', 1, false);


--
-- TOC entry 3130 (class 0 OID 0)
-- Dependencies: 186
-- Name: tb_avaliacao_relatorio_fk_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_avaliacao_relatorio_fk_seq', 1, false);


--
-- TOC entry 3131 (class 0 OID 0)
-- Dependencies: 187
-- Name: tb_avaliacao_sureg_fk_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_avaliacao_sureg_fk_seq', 1, false);


--
-- TOC entry 2773 (class 0 OID 17252)
-- Dependencies: 189
-- Data for Name: tb_capitulo; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3132 (class 0 OID 0)
-- Dependencies: 188
-- Name: tb_capitulo_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_capitulo_id_seq', 1, false);


--
-- TOC entry 2774 (class 0 OID 17259)
-- Dependencies: 190
-- Data for Name: tb_cargo; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--

INSERT INTO tb_cargo VALUES (1, 'Cargo');
INSERT INTO tb_cargo VALUES (7, 'SUPERINTENDENTE');


--
-- TOC entry 3133 (class 0 OID 0)
-- Dependencies: 191
-- Name: tb_cargo_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_cargo_id_seq', 7, true);


--
-- TOC entry 2776 (class 0 OID 17264)
-- Dependencies: 192
-- Data for Name: tb_categoria; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3134 (class 0 OID 0)
-- Dependencies: 193
-- Name: tb_categoria_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_categoria_id_seq', 1, false);


--
-- TOC entry 2778 (class 0 OID 17269)
-- Dependencies: 194
-- Data for Name: tb_criterio; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3135 (class 0 OID 0)
-- Dependencies: 195
-- Name: tb_criterio_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_criterio_id_seq', 1, false);


--
-- TOC entry 2780 (class 0 OID 17277)
-- Dependencies: 196
-- Data for Name: tb_diretoria; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 2781 (class 0 OID 17280)
-- Dependencies: 197
-- Data for Name: tb_especie_auditoria; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3136 (class 0 OID 0)
-- Dependencies: 198
-- Name: tb_especie_auditoria_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_especie_auditoria_id_seq', 1, false);


--
-- TOC entry 2783 (class 0 OID 17285)
-- Dependencies: 199
-- Data for Name: tb_feriado; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3137 (class 0 OID 0)
-- Dependencies: 200
-- Name: tb_feriado2_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_feriado2_id_seq', 1, false);


--
-- TOC entry 2785 (class 0 OID 17290)
-- Dependencies: 201
-- Data for Name: tb_funcao; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--

INSERT INTO tb_funcao VALUES (1, 'FUNÇÃO');


--
-- TOC entry 3138 (class 0 OID 0)
-- Dependencies: 202
-- Name: tb_funcao_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_funcao_id_seq', 1, true);


--
-- TOC entry 2787 (class 0 OID 17295)
-- Dependencies: 203
-- Data for Name: tb_homens_hora; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 2789 (class 0 OID 17300)
-- Dependencies: 205
-- Data for Name: tb_homens_hora_conf; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3139 (class 0 OID 0)
-- Dependencies: 204
-- Name: tb_homens_hora_conf_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_homens_hora_conf_id_seq', 1, false);


--
-- TOC entry 3140 (class 0 OID 0)
-- Dependencies: 206
-- Name: tb_homens_hora_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_homens_hora_id_seq', 1, false);


--
-- TOC entry 2791 (class 0 OID 17306)
-- Dependencies: 207
-- Data for Name: tb_imagem; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3141 (class 0 OID 0)
-- Dependencies: 208
-- Name: tb_imagem_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_imagem_id_seq', 1, false);


--
-- TOC entry 2793 (class 0 OID 17311)
-- Dependencies: 209
-- Data for Name: tb_item; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3142 (class 0 OID 0)
-- Dependencies: 210
-- Name: tb_item_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_item_id_seq', 1, false);


--
-- TOC entry 3143 (class 0 OID 0)
-- Dependencies: 212
-- Name: tb_log_entrada_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_log_entrada_id_seq', 1, false);


--
-- TOC entry 3144 (class 0 OID 0)
-- Dependencies: 213
-- Name: tb_log_entrada_id_seq1; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_log_entrada_id_seq1', 272, true);


--
-- TOC entry 2798 (class 0 OID 17327)
-- Dependencies: 214
-- Data for Name: tb_manifestacao; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3145 (class 0 OID 0)
-- Dependencies: 215
-- Name: tb_manifestacao_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_manifestacao_id_seq', 1, false);


--
-- TOC entry 2875 (class 0 OID 22000)
-- Dependencies: 293
-- Data for Name: tb_menu; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--

INSERT INTO tb_menu VALUES (470, 470, 0, 3, 0, 'PAINT', 'Módulo do PAINT', '', '', '', 47);
INSERT INTO tb_menu VALUES (519, 504, 2, 4, 0, 'Relatório de Avaliação do Auditor', 'Relatório de Avaliação do Auditor', '/siaudi2/RelatorioAvaliacao', '', '', 47);
INSERT INTO tb_menu VALUES (535, 504, 2, 5, 0, 'Registros de Acessos', 'Registros de Acessos', '/siaudi2/RelatorioSaida/RegistrosAcessosAjax', '', '', 47);
INSERT INTO tb_menu VALUES (506, 506, 0, 2, 0, 'Manifestação', 'Manifestação do Relatório', '/siaudi2/Manifestacao', '', '', 47);
INSERT INTO tb_menu VALUES (513, 512, 2, 1, 0, 'Gerenciar Status do Follow Up', 'Gerenciar Status do Follow Up', '/siaudi2/TipoStatus', '', '', 47);
INSERT INTO tb_menu VALUES (514, 514, 0, 6, 0, 'Follow Up', 'Follow Up', '/siaudi2/Resposta', '', '', 47);
INSERT INTO tb_menu VALUES (515, 515, 0, 7, 0, 'Permissões de Acesso', 'Permissões de Acesso', '', '', '', 47);
INSERT INTO tb_menu VALUES (540, 515, 1, 2, 0, 'Liberar acesso a Itens do Relatório', 'Liberar acesso a Itens do Relatório', '/siaudi2/RelatorioAcessoItem', '', '', 47);
INSERT INTO tb_menu VALUES (541, 515, 1, 1, 0, 'Gerenciar acesso de Superintendentes', 'Gerenciar acesso de Superintendentes', '', '', '', 47);
INSERT INTO tb_menu VALUES (517, 517, 0, 9, 0, 'RAINT', 'RAINT', '', '', '', 47);
INSERT INTO tb_menu VALUES (520, 517, 1, 2, 0, 'Relatórios Gerenciais', 'Relatórios Gerenciais', '', '', '', 47);
INSERT INTO tb_menu VALUES (529, 517, 1, 1, 0, 'RAINT', 'RAINT', '/siaudi2/Raint', '', '', 47);
INSERT INTO tb_menu VALUES (521, 520, 2, 7, 0, 'Recomendações por Gravidade', 'Relatório de Recomendações por Gravidade', '/siaudi2/RelatorioRaint/RecomendacaoGravidadeAjax', '', '', 47);
INSERT INTO tb_menu VALUES (523, 520, 2, 10, 0, 'Relatório de Resolutibilidade', 'Relatório de Resolutibilidade', '/siaudi2/RelatorioRaint/RecomendacaoSolucionadasAjax', '', '', 47);
INSERT INTO tb_menu VALUES (524, 520, 2, 6, 0, 'Recomendações por Categoria', 'Recomendações por Categoria', '/siaudi2/RelatorioRaint/RecomendacaoPorCategoriaAjax', '', '', 47);
INSERT INTO tb_menu VALUES (525, 520, 2, 2, 0, 'Recomendações de auditoria sem manifestação', 'Recomendações de auditoria sem manifestação', '/siaudi2/RelatorioRaint/RecomendacaoAuditoriaSemManifestacaoAjax', '', '', 47);
INSERT INTO tb_menu VALUES (526, 520, 2, 3, 0, 'Recomendações não avaliadas pelo auditor', 'Recomendações não avaliadas pelo auditor', '/siaudi2/RelatorioRaint/RecomendacaoNaoAvaliadaAuditorAjax', '', '', 47);
INSERT INTO tb_menu VALUES (527, 520, 2, 4, 0, 'Recomendações pendentes de resposta pelo auditado', 'Recomendações pendentes de resposta pelo auditado', '/siaudi2/RelatorioRaint/RecomendacaoPendenteRespostaAuditadoAjax', '', '', 47);
INSERT INTO tb_menu VALUES (528, 520, 2, 9, 0, 'Relatório da CGU', 'Relatório da CGU', '/siaudi2/RelatorioRaint/RelatorioCGUAjax', '', '', 47);
INSERT INTO tb_menu VALUES (530, 520, 2, 12, 0, 'Tempo de Execução dos Trabalhos por Auditor', 'Tempo de Execução dos Trabalhos por Auditor', '/siaudi2/RelatorioRaint/RelatorioTempoExecucaoTrabalhoAuditorAjax', '', '', 47);
INSERT INTO tb_menu VALUES (531, 520, 2, 5, 0, 'Recomendações por Ação Consolidado', 'Recomendações por Ação Consolidado', '/siaudi2/RelatorioRaint/RecomendacaoPorAcaoAjax', '', '', 47);
INSERT INTO tb_menu VALUES (532, 520, 2, 8, 0, 'Recomendações por SubCategoria', 'Recomendações por SubCategoria', '/siaudi2/RelatorioRaint/RecomendacaoPorSubCategoriaAjax', '', '', 47);
INSERT INTO tb_menu VALUES (533, 520, 2, 11, 0, 'Riscos por Objeto', 'Riscos por Objeto', '/siaudi2/RelatorioRaint/RelatorioRiscoPorObjetoAjax', '', '', 47);
INSERT INTO tb_menu VALUES (545, 520, 2, 1, 0, 'Descrição das Ações', 'Descrição das Ações', '/siaudi2/RelatorioRaint/DescricaoDasAcoesAjax', '', '', 47);
INSERT INTO tb_menu VALUES (457, 457, 0, 2, 0, 'Análise de Risco', 'Módulo de Análise de Risco', '', '', '', 47);
INSERT INTO tb_menu VALUES (461, 457, 1, 1, 0, 'Critério', 'Administrar Critérios', '', '', '', 47);
INSERT INTO tb_menu VALUES (463, 457, 1, 3, 0, 'Risco', 'Riscos Pré-Identificados', '', '', '', 47);
INSERT INTO tb_menu VALUES (468, 457, 1, 2, 0, 'Processo', 'Gerenciar Processo', '', '', '', 47);
INSERT INTO tb_menu VALUES (460, 458, 2, 1, 0, 'Espécie de Auditoria', 'Espécie de Auditoria', '/siaudi2/EspecieAuditoria', '', '', 47);
INSERT INTO tb_menu VALUES (465, 458, 2, 3, 0, 'Gerenciar Ação', 'Gerenciar Ação', '/siaudi2/Acao', '', '', 47);
INSERT INTO tb_menu VALUES (462, 461, 2, 1, 0, 'Tipo de Critério', 'Gerenciar Tipo de Critério', '/siaudi2/TipoCriterio', '', '', 47);
INSERT INTO tb_menu VALUES (466, 461, 2, 2, 0, 'Gerenciar Critério', 'Gerenciar Critério', '/siaudi2/Criterio', '', '', 47);
INSERT INTO tb_menu VALUES (471, 461, 2, 3, 0, 'Gerenciar Sub critério', 'Gerenciar Sub critério', '/siaudi2/Subcriterio', '', '', 47);
INSERT INTO tb_menu VALUES (467, 463, 2, 3, 0, 'Tabela de Riscos', 'Tabela de Riscos', '/siaudi2/Risco', '', '', 47);
INSERT INTO tb_menu VALUES (472, 463, 2, 2, 0, 'Tabela de Sub-Riscos', 'Tabela de Sub-Riscos', '/siaudi2/Subrisco', '', '', 47);
INSERT INTO tb_menu VALUES (459, 468, 2, 2, 0, 'Tipo de Processo', 'Gerenciar Tipo', '/siaudi2/TipoProcesso', '', '', 47);
INSERT INTO tb_menu VALUES (464, 468, 2, 4, 0, 'Riscos Pré-Identificados', 'Riscos Pré-Identificados', '/siaudi2/RiscoPre', '', '', 47);
INSERT INTO tb_menu VALUES (469, 468, 2, 3, 0, 'Gerenciar Processo', 'Gerenciar Processos', '/siaudi2/Processo', '', '', 47);
INSERT INTO tb_menu VALUES (458, 470, 1, 1, 0, 'Ação', 'Gerenciar Ação de Auditoria', '', '', '', 47);
INSERT INTO tb_menu VALUES (473, 470, 1, 2, 0, 'PAINT', 'Relatório do PAINT', '', '', '', 47);
INSERT INTO tb_menu VALUES (474, 473, 2, 3, 0, 'Gerenciar PAINT', 'Gerenciar Relatório do PAINT', '/siaudi2/Paint', '', '', 47);
INSERT INTO tb_menu VALUES (478, 473, 2, 1, 0, 'Tabela de Homens Hora (Configuração)', 'Tabela de Homens Hora (Configuração)', '/siaudi2/HomensHoraConf', '', '', 47);
INSERT INTO tb_menu VALUES (479, 473, 2, 2, 0, 'Tabela de Homens Hora', 'Tabela de Homens Hora', '/siaudi2/HomensHora', '', '', 47);
INSERT INTO tb_menu VALUES (475, 475, 0, 1, 0, 'Configurações', 'Gerenciamento de Auditores, e Tab de Apoio', '', '', '', 47);
INSERT INTO tb_menu VALUES (476, 475, 1, 8, 0, 'Usuários', 'Gerenciar Usuários', '', '', '', 47);
INSERT INTO tb_menu VALUES (484, 475, 1, 1, 0, 'Avaliação do Auditor', 'Avaliação do Auditor', '', '', '', 47);
INSERT INTO tb_menu VALUES (487, 475, 1, 2, 0, 'Categoria', 'Categoria', '/siaudi2/Categoria', '', '', 47);
INSERT INTO tb_menu VALUES (477, 476, 2, 4, 0, 'Gerenciar Usuários', 'Gerenciar Usuários', '/siaudi2/Usuario', '', '', 47);
INSERT INTO tb_menu VALUES (480, 476, 2, 3, 0, 'Gerenciar Perfil', 'Gerenciar Perfil', '/siaudi2/Perfil', '', '', 47);
INSERT INTO tb_menu VALUES (481, 476, 2, 1, 0, 'Gerenciar Núcleo', 'Gerenciar Núcleo', '/siaudi2/Nucleo', '', '', 47);
INSERT INTO tb_menu VALUES (482, 483, 1, 2, 0, 'Planejamento Específico', 'Planejamento Específico', '/siaudi2/PlanEspecifico', '', '', 47);
INSERT INTO tb_menu VALUES (483, 483, 0, 4, 0, 'Programas e Planejamento', 'Programas e Planejamento', '', '', '', 47);
INSERT INTO tb_menu VALUES (485, 484, 2, 1, 0, 'Gerenciar Critérios de Avaliação', 'Gerenciar Critérios de Avaliação', '/siaudi2/AvaliacaoCriterio', '', '', 47);
INSERT INTO tb_menu VALUES (486, 486, 0, 5, 0, 'Relatoria', 'Relatoria do SIAUDI', '', '', '', 47);
INSERT INTO tb_menu VALUES (490, 486, 1, 1, 0, 'Cadastros', 'Cadastros de Relatoria ', '', '', '', 47);
INSERT INTO tb_menu VALUES (488, 487, 2, 2, 0, 'Gerenciar Diretoria', 'Gerenciar Diretoria', '/siaudi2/Diretoria', '', '', 47);
INSERT INTO tb_menu VALUES (489, 487, 2, 1, 0, 'Gerenciar Categoria', 'Gerenciar Categoria', '/siaudi2/Categoria', '', '', 47);
INSERT INTO tb_menu VALUES (495, 475, 1, 6, 0, 'Recomendação', 'Tabelas de Apoio da Recomendação', '', '', '', 47);
INSERT INTO tb_menu VALUES (512, 475, 1, 4, 0, 'Follow Up', 'Configurações do Follow Up', '', '', '', 47);
INSERT INTO tb_menu VALUES (522, 475, 1, 5, 0, 'Objeto', 'Objeto', '/siaudi2/Objeto', '', '', 47);
INSERT INTO tb_menu VALUES (536, 475, 1, 0, 0, 'Sureg', 'Sureg', '', '', '', 47);
INSERT INTO tb_menu VALUES (539, 475, 1, 3, 0, 'Feriados', 'Feriados', '/siaudi2/Feriado', '', '', 47);
INSERT INTO tb_menu VALUES (542, 475, 1, 7, 0, 'Unidade Administrativa', 'Unidade Administrativa', '/siaudi2/UnidadeAdministrativa', '', '', 47);
INSERT INTO tb_menu VALUES (546, 476, 2, 2, 0, 'Gerenciar Cargo', 'Gerenciar Cargo', '/siaudi2/Cargo', '', '', 47);
INSERT INTO tb_menu VALUES (544, 483, 1, 1, 0, 'Programas de Auditoria', 'Programas de Auditoria', '/siaudi2/ProgramasAuditoria', '', '', 47);
INSERT INTO tb_menu VALUES (491, 490, 2, 2, 0, 'Gerenciar Relatório', 'Gerenciar Relatório', '/siaudi2/relatorio', '', '', 47);
INSERT INTO tb_menu VALUES (493, 490, 2, 3, 0, 'Gerenciar Capítulo', 'Gerenciar Capítulo', '/siaudi2/Capitulo', '', '', 47);
INSERT INTO tb_menu VALUES (494, 490, 2, 4, 0, 'Gerenciar Item', 'Gerenciar Item', '/siaudi2/Item', '', '', 47);
INSERT INTO tb_menu VALUES (500, 490, 2, 5, 0, 'Gerenciar Recomendação', 'Gerenciar Recomendação', '/siaudi2/Recomendacao', '', '', 47);
INSERT INTO tb_menu VALUES (534, 490, 2, 1, 0, 'Gerenciar Riscos Pós Identificados', 'Gerenciar Riscos Pós Identificados', '/siaudi2/RiscoPos', '', '', 47);
INSERT INTO tb_menu VALUES (496, 495, 2, 1, 0, 'Gerenciar Tipo de Recomendação', 'Gerenciar Tipo de Recomendação', '/siaudi2/RecomendacaoTipo', '', '', 47);
INSERT INTO tb_menu VALUES (497, 495, 2, 2, 0, 'Gerenciar Categoria', 'Categoria da Recomendação', '/siaudi2/RecomendacaoCategoria', '', '', 47);
INSERT INTO tb_menu VALUES (498, 495, 2, 3, 0, 'Gerenciar Subcategoria', 'Subcategoria da Recomendação', '/siaudi2/RecomendacaoSubcategoria', '', '', 47);
INSERT INTO tb_menu VALUES (499, 495, 2, 4, 0, 'Gerenciar Gravidade', 'Gerenciar Gravidade da Recomendação', '/siaudi2/RecomendacaoGravidade', '', '', 47);
INSERT INTO tb_menu VALUES (518, 495, 2, 5, 0, 'Gerenciar Padrão da Recomendação', 'Gerenciar Padrão da Recomendação', '/siaudi2/RecomendacaoPadrao', '', '', 47);
INSERT INTO tb_menu VALUES (492, 501, 1, 3, 0, 'Despacho', 'Gerenciar Despacho', '/siaudi2/RelatorioDespacho/admin/1', '', '', 47);
INSERT INTO tb_menu VALUES (501, 501, 0, 2, 0, 'Auditorias', 'Gerenciamento de Auditorias', '', '', '', 47);
INSERT INTO tb_menu VALUES (502, 501, 1, 1, 0, 'Finalizar Relatório', 'Finalizar Relatório', '/siaudi2/RelatorioFinaliza', '', '', 47);
INSERT INTO tb_menu VALUES (507, 501, 1, 2, 0, 'Responder Manifestação', 'Responder Manifestação', '/siaudi2/Manifestacao/ResponderManifestacaoAjax', '', '', 47);
INSERT INTO tb_menu VALUES (509, 501, 1, 2, 0, 'Prorrogar Prazo', 'Prorrogar Prazo', '/siaudi2/Relatorio/prorrogarPrazoAjax', '', '', 47);
INSERT INTO tb_menu VALUES (510, 501, 1, 1, 0, 'Homologar Relatório', 'Homologar Relatório', '/siaudi2/Relatorio/homologarAjax', '', '', 47);
INSERT INTO tb_menu VALUES (511, 501, 1, 4, 0, 'Reiniciar Contagem', 'Reiniciar Contagem', '/siaudi2/Relatorio/reiniciarContagemAjax', '', '', 47);
INSERT INTO tb_menu VALUES (538, 501, 1, 4, 0, 'Pré Finalizar Relatório', 'Pré Finalizar Relatório', '/siaudi2/RelatorioPreFinaliza', '', '', 47);
INSERT INTO tb_menu VALUES (503, 503, 0, 7, 0, 'Relatórios', 'Relatórios de saída', '', '', '', 47);
INSERT INTO tb_menu VALUES (504, 503, 1, 1, 0, 'Relatórios', 'Relatórios de Saída', '', '', '', 47);
INSERT INTO tb_menu VALUES (505, 504, 2, 1, 0, 'Relatórios de Auditoria', 'Relatórios de Auditoria', '/siaudi2/RelatorioSaida', '', '', 47);
INSERT INTO tb_menu VALUES (508, 504, 2, 2, 0, 'Relatórios de Manifestações', 'Relatórios de Manifestações', '/siaudi2/Manifestacao/ManifestacaoSaidaAjax', '', '', 47);
INSERT INTO tb_menu VALUES (516, 504, 2, 3, 0, 'Acompanhamento de Pendências', 'Acompanhamento de Pendências', '/siaudi2/RelatorioSaida/RelatorioPendenciasAjax', '', '', 47);
INSERT INTO tb_menu VALUES (547, 476, 2, 2, 0, 'Gerenciar Função', 'Gerenciar Função', '/siaudi2/Funcao', '', '', 47);
INSERT INTO tb_menu VALUES (548, 501, 1, 5, 0, 'Regularizar Relatório', 'Regularizar Relatório', '/siaudi2/relatorioRegulariza', '', '', 47);
INSERT INTO tb_menu VALUES (537, 536, 2, 0, 0, 'Gerenciar Sureg', 'Gerenciar Sureg', '/siaudi2/Sureg', '', '', 47);
INSERT INTO tb_menu VALUES (543, 542, 2, 0, 0, 'Gerenciar Unidade Administrativa', 'Gerenciar Unidade Administrativa', '/siaudi2/UnidadeAdministrativa', '', '', 47);


--
-- TOC entry 2877 (class 0 OID 22008)
-- Dependencies: 295
-- Data for Name: tb_menu_perfil; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--

INSERT INTO tb_menu_perfil VALUES (514, 148);
INSERT INTO tb_menu_perfil VALUES (486, 148);
INSERT INTO tb_menu_perfil VALUES (503, 148);
INSERT INTO tb_menu_perfil VALUES (470, 148);
INSERT INTO tb_menu_perfil VALUES (483, 148);
INSERT INTO tb_menu_perfil VALUES (517, 148);
INSERT INTO tb_menu_perfil VALUES (504, 148);
INSERT INTO tb_menu_perfil VALUES (529, 148);
INSERT INTO tb_menu_perfil VALUES (473, 148);
INSERT INTO tb_menu_perfil VALUES (490, 148);
INSERT INTO tb_menu_perfil VALUES (482, 148);
INSERT INTO tb_menu_perfil VALUES (544, 148);
INSERT INTO tb_menu_perfil VALUES (516, 148);
INSERT INTO tb_menu_perfil VALUES (491, 148);
INSERT INTO tb_menu_perfil VALUES (494, 148);
INSERT INTO tb_menu_perfil VALUES (508, 148);
INSERT INTO tb_menu_perfil VALUES (505, 148);
INSERT INTO tb_menu_perfil VALUES (519, 148);
INSERT INTO tb_menu_perfil VALUES (534, 148);
INSERT INTO tb_menu_perfil VALUES (535, 148);
INSERT INTO tb_menu_perfil VALUES (493, 148);
INSERT INTO tb_menu_perfil VALUES (474, 148);
INSERT INTO tb_menu_perfil VALUES (500, 148);
INSERT INTO tb_menu_perfil VALUES (514, 149);
INSERT INTO tb_menu_perfil VALUES (483, 149);
INSERT INTO tb_menu_perfil VALUES (517, 149);
INSERT INTO tb_menu_perfil VALUES (503, 149);
INSERT INTO tb_menu_perfil VALUES (544, 149);
INSERT INTO tb_menu_perfil VALUES (504, 149);
INSERT INTO tb_menu_perfil VALUES (520, 149);
INSERT INTO tb_menu_perfil VALUES (482, 149);
INSERT INTO tb_menu_perfil VALUES (528, 149);
INSERT INTO tb_menu_perfil VALUES (505, 149);
INSERT INTO tb_menu_perfil VALUES (516, 149);
INSERT INTO tb_menu_perfil VALUES (503, 150);
INSERT INTO tb_menu_perfil VALUES (470, 150);
INSERT INTO tb_menu_perfil VALUES (517, 150);
INSERT INTO tb_menu_perfil VALUES (475, 150);
INSERT INTO tb_menu_perfil VALUES (483, 150);
INSERT INTO tb_menu_perfil VALUES (501, 150);
INSERT INTO tb_menu_perfil VALUES (504, 150);
INSERT INTO tb_menu_perfil VALUES (548, 150);
INSERT INTO tb_menu_perfil VALUES (512, 150);
INSERT INTO tb_menu_perfil VALUES (509, 150);
INSERT INTO tb_menu_perfil VALUES (487, 150);
INSERT INTO tb_menu_perfil VALUES (511, 150);
INSERT INTO tb_menu_perfil VALUES (495, 150);
INSERT INTO tb_menu_perfil VALUES (482, 150);
INSERT INTO tb_menu_perfil VALUES (529, 150);
INSERT INTO tb_menu_perfil VALUES (539, 150);
INSERT INTO tb_menu_perfil VALUES (492, 150);
INSERT INTO tb_menu_perfil VALUES (522, 150);
INSERT INTO tb_menu_perfil VALUES (484, 150);
INSERT INTO tb_menu_perfil VALUES (542, 150);
INSERT INTO tb_menu_perfil VALUES (544, 150);
INSERT INTO tb_menu_perfil VALUES (520, 150);
INSERT INTO tb_menu_perfil VALUES (463, 150);
INSERT INTO tb_menu_perfil VALUES (458, 150);
INSERT INTO tb_menu_perfil VALUES (473, 150);
INSERT INTO tb_menu_perfil VALUES (510, 150);
INSERT INTO tb_menu_perfil VALUES (476, 150);
INSERT INTO tb_menu_perfil VALUES (516, 150);
INSERT INTO tb_menu_perfil VALUES (545, 150);
INSERT INTO tb_menu_perfil VALUES (532, 150);
INSERT INTO tb_menu_perfil VALUES (521, 150);
INSERT INTO tb_menu_perfil VALUES (527, 150);
INSERT INTO tb_menu_perfil VALUES (530, 150);
INSERT INTO tb_menu_perfil VALUES (480, 150);
INSERT INTO tb_menu_perfil VALUES (498, 150);
INSERT INTO tb_menu_perfil VALUES (465, 150);
INSERT INTO tb_menu_perfil VALUES (505, 150);
INSERT INTO tb_menu_perfil VALUES (496, 150);
INSERT INTO tb_menu_perfil VALUES (519, 150);
INSERT INTO tb_menu_perfil VALUES (524, 150);
INSERT INTO tb_menu_perfil VALUES (508, 150);
INSERT INTO tb_menu_perfil VALUES (489, 150);
INSERT INTO tb_menu_perfil VALUES (528, 150);
INSERT INTO tb_menu_perfil VALUES (523, 150);
INSERT INTO tb_menu_perfil VALUES (526, 150);
INSERT INTO tb_menu_perfil VALUES (499, 150);
INSERT INTO tb_menu_perfil VALUES (518, 150);
INSERT INTO tb_menu_perfil VALUES (535, 150);
INSERT INTO tb_menu_perfil VALUES (479, 150);
INSERT INTO tb_menu_perfil VALUES (525, 150);
INSERT INTO tb_menu_perfil VALUES (533, 150);
INSERT INTO tb_menu_perfil VALUES (481, 150);
INSERT INTO tb_menu_perfil VALUES (531, 150);
INSERT INTO tb_menu_perfil VALUES (513, 150);
INSERT INTO tb_menu_perfil VALUES (485, 150);
INSERT INTO tb_menu_perfil VALUES (477, 150);
INSERT INTO tb_menu_perfil VALUES (474, 150);
INSERT INTO tb_menu_perfil VALUES (460, 150);
INSERT INTO tb_menu_perfil VALUES (515, 151);
INSERT INTO tb_menu_perfil VALUES (506, 151);
INSERT INTO tb_menu_perfil VALUES (514, 151);
INSERT INTO tb_menu_perfil VALUES (503, 151);
INSERT INTO tb_menu_perfil VALUES (540, 151);
INSERT INTO tb_menu_perfil VALUES (504, 151);
INSERT INTO tb_menu_perfil VALUES (508, 151);
INSERT INTO tb_menu_perfil VALUES (505, 151);
INSERT INTO tb_menu_perfil VALUES (503, 152);
INSERT INTO tb_menu_perfil VALUES (514, 152);
INSERT INTO tb_menu_perfil VALUES (504, 152);
INSERT INTO tb_menu_perfil VALUES (505, 152);
INSERT INTO tb_menu_perfil VALUES (483, 153);
INSERT INTO tb_menu_perfil VALUES (544, 153);
INSERT INTO tb_menu_perfil VALUES (483, 154);
INSERT INTO tb_menu_perfil VALUES (544, 154);
INSERT INTO tb_menu_perfil VALUES (514, 155);
INSERT INTO tb_menu_perfil VALUES (483, 155);
INSERT INTO tb_menu_perfil VALUES (503, 155);
INSERT INTO tb_menu_perfil VALUES (544, 155);
INSERT INTO tb_menu_perfil VALUES (504, 155);
INSERT INTO tb_menu_perfil VALUES (505, 155);
INSERT INTO tb_menu_perfil VALUES (516, 155);
INSERT INTO tb_menu_perfil VALUES (503, 159);
INSERT INTO tb_menu_perfil VALUES (514, 159);
INSERT INTO tb_menu_perfil VALUES (504, 159);
INSERT INTO tb_menu_perfil VALUES (505, 159);
INSERT INTO tb_menu_perfil VALUES (516, 159);
INSERT INTO tb_menu_perfil VALUES (483, 160);
INSERT INTO tb_menu_perfil VALUES (501, 160);
INSERT INTO tb_menu_perfil VALUES (470, 160);
INSERT INTO tb_menu_perfil VALUES (475, 160);
INSERT INTO tb_menu_perfil VALUES (517, 160);
INSERT INTO tb_menu_perfil VALUES (457, 160);
INSERT INTO tb_menu_perfil VALUES (486, 160);
INSERT INTO tb_menu_perfil VALUES (503, 160);
INSERT INTO tb_menu_perfil VALUES (514, 160);
INSERT INTO tb_menu_perfil VALUES (473, 160);
INSERT INTO tb_menu_perfil VALUES (492, 160);
INSERT INTO tb_menu_perfil VALUES (522, 160);
INSERT INTO tb_menu_perfil VALUES (512, 160);
INSERT INTO tb_menu_perfil VALUES (484, 160);
INSERT INTO tb_menu_perfil VALUES (476, 160);
INSERT INTO tb_menu_perfil VALUES (458, 160);
INSERT INTO tb_menu_perfil VALUES (495, 160);
INSERT INTO tb_menu_perfil VALUES (539, 160);
INSERT INTO tb_menu_perfil VALUES (502, 160);
INSERT INTO tb_menu_perfil VALUES (507, 160);
INSERT INTO tb_menu_perfil VALUES (461, 160);
INSERT INTO tb_menu_perfil VALUES (542, 160);
INSERT INTO tb_menu_perfil VALUES (468, 160);
INSERT INTO tb_menu_perfil VALUES (504, 160);
INSERT INTO tb_menu_perfil VALUES (520, 160);
INSERT INTO tb_menu_perfil VALUES (529, 160);
INSERT INTO tb_menu_perfil VALUES (487, 160);
INSERT INTO tb_menu_perfil VALUES (544, 160);
INSERT INTO tb_menu_perfil VALUES (490, 160);
INSERT INTO tb_menu_perfil VALUES (482, 160);
INSERT INTO tb_menu_perfil VALUES (463, 160);
INSERT INTO tb_menu_perfil VALUES (465, 160);
INSERT INTO tb_menu_perfil VALUES (466, 160);
INSERT INTO tb_menu_perfil VALUES (535, 160);
INSERT INTO tb_menu_perfil VALUES (505, 160);
INSERT INTO tb_menu_perfil VALUES (498, 160);
INSERT INTO tb_menu_perfil VALUES (472, 160);
INSERT INTO tb_menu_perfil VALUES (513, 160);
INSERT INTO tb_menu_perfil VALUES (527, 160);
INSERT INTO tb_menu_perfil VALUES (471, 160);
INSERT INTO tb_menu_perfil VALUES (467, 160);
INSERT INTO tb_menu_perfil VALUES (533, 160);
INSERT INTO tb_menu_perfil VALUES (462, 160);
INSERT INTO tb_menu_perfil VALUES (474, 160);
INSERT INTO tb_menu_perfil VALUES (528, 160);
INSERT INTO tb_menu_perfil VALUES (497, 160);
INSERT INTO tb_menu_perfil VALUES (494, 160);
INSERT INTO tb_menu_perfil VALUES (519, 160);
INSERT INTO tb_menu_perfil VALUES (496, 160);
INSERT INTO tb_menu_perfil VALUES (518, 160);
INSERT INTO tb_menu_perfil VALUES (531, 160);
INSERT INTO tb_menu_perfil VALUES (464, 160);
INSERT INTO tb_menu_perfil VALUES (530, 160);
INSERT INTO tb_menu_perfil VALUES (526, 160);
INSERT INTO tb_menu_perfil VALUES (491, 160);
INSERT INTO tb_menu_perfil VALUES (477, 160);
INSERT INTO tb_menu_perfil VALUES (489, 160);
INSERT INTO tb_menu_perfil VALUES (499, 160);
INSERT INTO tb_menu_perfil VALUES (534, 160);
INSERT INTO tb_menu_perfil VALUES (547, 160);
INSERT INTO tb_menu_perfil VALUES (469, 160);
INSERT INTO tb_menu_perfil VALUES (523, 160);
INSERT INTO tb_menu_perfil VALUES (479, 160);
INSERT INTO tb_menu_perfil VALUES (485, 160);
INSERT INTO tb_menu_perfil VALUES (481, 160);
INSERT INTO tb_menu_perfil VALUES (460, 160);
INSERT INTO tb_menu_perfil VALUES (532, 160);
INSERT INTO tb_menu_perfil VALUES (524, 160);
INSERT INTO tb_menu_perfil VALUES (459, 160);
INSERT INTO tb_menu_perfil VALUES (500, 160);
INSERT INTO tb_menu_perfil VALUES (480, 160);
INSERT INTO tb_menu_perfil VALUES (493, 160);
INSERT INTO tb_menu_perfil VALUES (516, 160);
INSERT INTO tb_menu_perfil VALUES (546, 160);
INSERT INTO tb_menu_perfil VALUES (525, 160);
INSERT INTO tb_menu_perfil VALUES (521, 160);
INSERT INTO tb_menu_perfil VALUES (508, 160);
INSERT INTO tb_menu_perfil VALUES (545, 160);
INSERT INTO tb_menu_perfil VALUES (514, 161);
INSERT INTO tb_menu_perfil VALUES (503, 161);
INSERT INTO tb_menu_perfil VALUES (470, 161);
INSERT INTO tb_menu_perfil VALUES (483, 161);
INSERT INTO tb_menu_perfil VALUES (517, 161);
INSERT INTO tb_menu_perfil VALUES (501, 161);
INSERT INTO tb_menu_perfil VALUES (473, 161);
INSERT INTO tb_menu_perfil VALUES (529, 161);
INSERT INTO tb_menu_perfil VALUES (504, 161);
INSERT INTO tb_menu_perfil VALUES (492, 161);
INSERT INTO tb_menu_perfil VALUES (482, 161);
INSERT INTO tb_menu_perfil VALUES (544, 161);
INSERT INTO tb_menu_perfil VALUES (538, 161);
INSERT INTO tb_menu_perfil VALUES (474, 161);
INSERT INTO tb_menu_perfil VALUES (505, 161);
INSERT INTO tb_menu_perfil VALUES (486, 161);
INSERT INTO tb_menu_perfil VALUES (490, 161);
INSERT INTO tb_menu_perfil VALUES (491, 161);
INSERT INTO tb_menu_perfil VALUES (494, 161);
INSERT INTO tb_menu_perfil VALUES (493, 161);
INSERT INTO tb_menu_perfil VALUES (500, 161);


--
-- TOC entry 3146 (class 0 OID 0)
-- Dependencies: 292
-- Name: tb_menu_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_menu_seq', 1, false);


--
-- TOC entry 2879 (class 0 OID 22013)
-- Dependencies: 297
-- Data for Name: tb_modulo; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--

INSERT INTO tb_modulo VALUES (317, 47, 'Risco', 'Tabela de Riscos');
INSERT INTO tb_modulo VALUES (318, 47, 'RiscoPos', 'Riscos Pós Identificados');
INSERT INTO tb_modulo VALUES (319, 47, 'RiscoPre', 'Riscos Pré-identificados');
INSERT INTO tb_modulo VALUES (320, 47, 'Tempo de Execução dos Trabalhos por Auditor', 'Tempo de Execução dos Trabalhos por Auditor');
INSERT INTO tb_modulo VALUES (321, 47, 'TipoCriterio', 'Gerenciar Tipo de Critério');
INSERT INTO tb_modulo VALUES (322, 47, 'TipoProcesso', 'Gerenciar Tipos de Processo');
INSERT INTO tb_modulo VALUES (323, 47, 'TipoStatus', 'Status do Follow up');
INSERT INTO tb_modulo VALUES (324, 47, 'RecomendacaoCategoria', 'Tabela de Apoio - Categorias da Recomendação');
INSERT INTO tb_modulo VALUES (325, 47, 'RecomendacaoGravidade', 'Tabela de Apoio da Recomendação  - Gravidade');
INSERT INTO tb_modulo VALUES (326, 47, 'RecomendacaoSubcategoria', 'Tabela de Apoio - Subcategorias da Recomendação');
INSERT INTO tb_modulo VALUES (265, 47, 'RelatorioAcessoItem', 'Administrar Permissão de Acesso do Item do Relatório');
INSERT INTO tb_modulo VALUES (266, 47, 'Avaliacao', 'Avaliação do Auditor');
INSERT INTO tb_modulo VALUES (267, 47, 'Acao', 'Cadastrar Ação');
INSERT INTO tb_modulo VALUES (268, 47, 'Criterio', 'Cadastrar Critério');
INSERT INTO tb_modulo VALUES (269, 47, 'Processo', 'Cadastrar Processo');
INSERT INTO tb_modulo VALUES (270, 47, 'CarregaItemAgrupadoPorCapituloAjax', 'Carrega Item Agrupado por Capitulo');
INSERT INTO tb_modulo VALUES (271, 47, 'Feriado', 'Feriado');
INSERT INTO tb_modulo VALUES (272, 47, 'RelatorioFinaliza', 'Finalização do Relatório');
INSERT INTO tb_modulo VALUES (273, 47, 'Resposta', 'Follow up');
INSERT INTO tb_modulo VALUES (274, 47, 'Auditor', 'Gerenciar Auditor');
INSERT INTO tb_modulo VALUES (275, 47, 'Capitulo', 'Gerenciar Capítulo');
INSERT INTO tb_modulo VALUES (276, 47, 'Cargo', 'Gerenciar Cargo');
INSERT INTO tb_modulo VALUES (277, 47, 'Categoria', 'Gerenciar Categoria');
INSERT INTO tb_modulo VALUES (278, 47, 'AvaliacaoCriterio', 'Gerenciar Critério de Avaliação do Auditor');
INSERT INTO tb_modulo VALUES (279, 47, 'RelatorioDespacho', 'Gerenciar Despacho');
INSERT INTO tb_modulo VALUES (280, 47, 'EspecieAuditoria', 'Gerenciar Espécie de Auditoria');
INSERT INTO tb_modulo VALUES (281, 47, 'Funcao', 'Gerenciar Função');
INSERT INTO tb_modulo VALUES (282, 47, 'HomensHora', 'Gerenciar Homens Hora');
INSERT INTO tb_modulo VALUES (283, 47, 'HomensHoraConf', 'Gerenciar Homens Hora (configuração)');
INSERT INTO tb_modulo VALUES (284, 47, 'Item', 'Gerenciar Item');
INSERT INTO tb_modulo VALUES (285, 47, 'Nucleo', 'Gerenciar Nucleo');
INSERT INTO tb_modulo VALUES (286, 47, 'PlanEspecifico', 'Gerenciar Planejamento Específico');
INSERT INTO tb_modulo VALUES (287, 47, 'Recomendacao', 'Gerenciar Recomendação');
INSERT INTO tb_modulo VALUES (288, 47, 'Relatorio', 'Gerenciar Relatoria');
INSERT INTO tb_modulo VALUES (289, 47, 'Paint', 'Gerenciar Relatório do PAINT');
INSERT INTO tb_modulo VALUES (290, 47, 'Subcriterio', 'Gerenciar Subcritério');
INSERT INTO tb_modulo VALUES (291, 47, 'Subrisco', 'Gerenciar Sub-risco');
INSERT INTO tb_modulo VALUES (292, 47, 'Gerenciar Sureg', 'Gerenciar Sureg');
INSERT INTO tb_modulo VALUES (293, 47, 'TipoCriterio', 'Gerenciar Tipo de Critério');
INSERT INTO tb_modulo VALUES (294, 47, 'RecomendacaoTipo', 'Gerenciar Tipo de Recomendação');
INSERT INTO tb_modulo VALUES (295, 47, 'TipoProcesso', 'Gerenciar Tipos de Processo');
INSERT INTO tb_modulo VALUES (296, 47, 'UnidadeAdministrativa', 'Gerenciar Unidade Administrativa');
INSERT INTO tb_modulo VALUES (297, 47, 'usuario', 'Gerenciar Usuários');
INSERT INTO tb_modulo VALUES (298, 47, 'Manifestacao', 'Manifestação do Relatório');
INSERT INTO tb_modulo VALUES (299, 47, 'Objeto', 'Objeto');
INSERT INTO tb_modulo VALUES (300, 47, 'RecomendacaoPadrao', 'Padrão da Recomendação para pré-cadastro');
INSERT INTO tb_modulo VALUES (301, 47, 'RelatorioPreFinaliza', 'Pré Finalizar Relatório');
INSERT INTO tb_modulo VALUES (302, 47, 'Raint', 'Raint');
INSERT INTO tb_modulo VALUES (303, 47, 'RelatorioRegulariza', 'Regularizar Relatório');
INSERT INTO tb_modulo VALUES (304, 47, 'ReiniciarContagem', 'Reiniciar Contagem dos Itens');
INSERT INTO tb_modulo VALUES (305, 47, 'Relatório da CGU', 'Relatório da CGU');
INSERT INTO tb_modulo VALUES (306, 47, 'RelatorioAvaliacao', 'Relatório de Avalição do Auditor');
INSERT INTO tb_modulo VALUES (307, 47, 'Relatório das recomendações de auditoria sem manifestação', 'Relatório das recomendações de auditoria sem manifestação');
INSERT INTO tb_modulo VALUES (308, 47, 'Relatório de recomendações não avaliadas pelo auditor', 'Relatório de recomendações não avaliadas pelo auditor');
INSERT INTO tb_modulo VALUES (309, 47, 'Relatório de Recomendações por ação', 'Relatório de Recomendações por ação');
INSERT INTO tb_modulo VALUES (310, 47, 'Relatório de Recomendações por categoria', 'Relatório de Recomendações por categoria');
INSERT INTO tb_modulo VALUES (311, 47, 'Relatório de Recomendações por gravidade', 'Relatório de Recomendações por gravidade');
INSERT INTO tb_modulo VALUES (312, 47, 'Relatório de Recomendações por subcategoria', 'Relatório de Recomendações por subcategoria');
INSERT INTO tb_modulo VALUES (313, 47, 'Relatório das recomendações pendentes de resposta pelo auditado', 'Relatório das recomendações pendentes de resposta pelo auditado');
INSERT INTO tb_modulo VALUES (314, 47, 'Relatório de Resolutibilidade', 'Relatório de Resolutibilidade');
INSERT INTO tb_modulo VALUES (315, 47, 'Relatório de Riscos por Objeto', 'Relatório de Riscos por Objeto');
INSERT INTO tb_modulo VALUES (316, 47, 'RelatorioSaida', 'Relatório Saída');


--
-- TOC entry 3147 (class 0 OID 0)
-- Dependencies: 296
-- Name: tb_modulo_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_modulo_seq', 1, false);


--
-- TOC entry 2801 (class 0 OID 17337)
-- Dependencies: 217
-- Data for Name: tb_nucleo; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--

INSERT INTO tb_nucleo VALUES (1, 'MATRIZ');


--
-- TOC entry 3148 (class 0 OID 0)
-- Dependencies: 216
-- Name: tb_nucleo_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_nucleo_id_seq', 4, true);


--
-- TOC entry 2803 (class 0 OID 17343)
-- Dependencies: 219
-- Data for Name: tb_objeto; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3149 (class 0 OID 0)
-- Dependencies: 218
-- Name: tb_objeto_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_objeto_id_seq', 1, false);


--
-- TOC entry 3150 (class 0 OID 0)
-- Dependencies: 220
-- Name: tb_objeto_id_seq1; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_objeto_id_seq1', 1, false);


--
-- TOC entry 2805 (class 0 OID 17349)
-- Dependencies: 221
-- Data for Name: tb_paint; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3151 (class 0 OID 0)
-- Dependencies: 222
-- Name: tb_paint_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_paint_id_seq', 1, false);


--
-- TOC entry 2808 (class 0 OID 17359)
-- Dependencies: 224
-- Data for Name: tb_perfil; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--

INSERT INTO tb_perfil VALUES (148, 'Permissão de Auditor', 'SIAUDI_AUDITOR', 47);
INSERT INTO tb_perfil VALUES (149, 'Permissão Consulta', 'SIAUDI_CGU', 47);
INSERT INTO tb_perfil VALUES (150, 'Permissão de chefe de auditoria', 'SIAUDI_CHEFE_AUDITORIA', 47);
INSERT INTO tb_perfil VALUES (151, 'Permissão de superintendente', 'SIAUDI_CLIENTE', 47);
INSERT INTO tb_perfil VALUES (152, 'Permissão de acesso a item', 'SIAUDI_CLIENTE_ITEM', 47);
INSERT INTO tb_perfil VALUES (153, 'Permissão de conselho de administração', 'SIAUDI_CONADM', 47);
INSERT INTO tb_perfil VALUES (154, 'Permissão de conselho fiscal', 'SIAUDI_CONFIS', 47);
INSERT INTO tb_perfil VALUES (160, 'Permissão de gerente.', 'SIAUDI_GERENTE', 47);
INSERT INTO tb_perfil VALUES (159, 'Permissão de chefe de gabinete.', 'SIAUDI_GABIN', 47);
INSERT INTO tb_perfil VALUES (161, 'Permissão de gerente de núcleo', 'SIAUDI_GERENTE_NUCLEO', 47);
INSERT INTO tb_perfil VALUES (155, 'Permissão de Diretor.', 'SIAUDI_DIRETOR', 47);


--
-- TOC entry 3152 (class 0 OID 0)
-- Dependencies: 223
-- Name: tb_perfil_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_perfil_id_seq', 1, false);


--
-- TOC entry 2809 (class 0 OID 17363)
-- Dependencies: 225
-- Data for Name: tb_plan_especifico; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 2810 (class 0 OID 17371)
-- Dependencies: 226
-- Data for Name: tb_plan_especifico_auditor; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3153 (class 0 OID 0)
-- Dependencies: 227
-- Name: tb_plan_especifico_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_plan_especifico_id_seq', 1, false);


--
-- TOC entry 2813 (class 0 OID 17378)
-- Dependencies: 229
-- Data for Name: tb_processo; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 2814 (class 0 OID 17382)
-- Dependencies: 230
-- Data for Name: tb_processo_especie_auditoria; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3154 (class 0 OID 0)
-- Dependencies: 228
-- Name: tb_processo_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_processo_id_seq', 1, false);


--
-- TOC entry 2815 (class 0 OID 17385)
-- Dependencies: 231
-- Data for Name: tb_processo_risco_pre; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 2817 (class 0 OID 17390)
-- Dependencies: 233
-- Data for Name: tb_raint; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3155 (class 0 OID 0)
-- Dependencies: 232
-- Name: tb_raint_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_raint_id_seq', 1, false);


--
-- TOC entry 3156 (class 0 OID 0)
-- Dependencies: 234
-- Name: tb_raint_id_seq1; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_raint_id_seq1', 1, false);


--
-- TOC entry 2819 (class 0 OID 17399)
-- Dependencies: 235
-- Data for Name: tb_recomendacao; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 2821 (class 0 OID 17407)
-- Dependencies: 237
-- Data for Name: tb_recomendacao_categoria; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3157 (class 0 OID 0)
-- Dependencies: 236
-- Name: tb_recomendacao_categoria_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_recomendacao_categoria_id_seq', 1, false);


--
-- TOC entry 2823 (class 0 OID 17413)
-- Dependencies: 239
-- Data for Name: tb_recomendacao_gravidade; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3158 (class 0 OID 0)
-- Dependencies: 238
-- Name: tb_recomendacao_gravidade_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_recomendacao_gravidade_id_seq', 1, false);


--
-- TOC entry 3159 (class 0 OID 0)
-- Dependencies: 240
-- Name: tb_recomendacao_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_recomendacao_id_seq', 1, false);


--
-- TOC entry 3160 (class 0 OID 0)
-- Dependencies: 241
-- Name: tb_recomendacao_id_seq1; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_recomendacao_id_seq1', 1, false);


--
-- TOC entry 2827 (class 0 OID 17423)
-- Dependencies: 243
-- Data for Name: tb_recomendacao_padrao; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3161 (class 0 OID 0)
-- Dependencies: 242
-- Name: tb_recomendacao_padrao_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_recomendacao_padrao_id_seq', 1, false);


--
-- TOC entry 3162 (class 0 OID 0)
-- Dependencies: 244
-- Name: tb_recomendacao_padrao_id_seq1; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_recomendacao_padrao_id_seq1', 1, false);


--
-- TOC entry 2830 (class 0 OID 17434)
-- Dependencies: 246
-- Data for Name: tb_recomendacao_subcategoria; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3163 (class 0 OID 0)
-- Dependencies: 245
-- Name: tb_recomendacao_subcategoria_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_recomendacao_subcategoria_id_seq', 1, false);


--
-- TOC entry 2832 (class 0 OID 17440)
-- Dependencies: 248
-- Data for Name: tb_recomendacao_tipo; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3164 (class 0 OID 0)
-- Dependencies: 247
-- Name: tb_recomendacao_tipo_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_recomendacao_tipo_id_seq', 1, false);


--
-- TOC entry 2833 (class 0 OID 17444)
-- Dependencies: 249
-- Data for Name: tb_relatorio; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 2834 (class 0 OID 17450)
-- Dependencies: 250
-- Data for Name: tb_relatorio_acesso; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 2836 (class 0 OID 17455)
-- Dependencies: 252
-- Data for Name: tb_relatorio_acesso_item; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3165 (class 0 OID 0)
-- Dependencies: 251
-- Name: tb_relatorio_acesso_item_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_relatorio_acesso_item_id_seq', 1, false);


--
-- TOC entry 2837 (class 0 OID 17460)
-- Dependencies: 253
-- Data for Name: tb_relatorio_area; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 2838 (class 0 OID 17463)
-- Dependencies: 254
-- Data for Name: tb_relatorio_auditor; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3166 (class 0 OID 0)
-- Dependencies: 255
-- Name: tb_relatorio_cabecalho_rodape_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_relatorio_cabecalho_rodape_id_seq', 1, false);


--
-- TOC entry 2840 (class 0 OID 17468)
-- Dependencies: 256
-- Data for Name: tb_relatorio_despacho; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 2841 (class 0 OID 17475)
-- Dependencies: 257
-- Data for Name: tb_relatorio_diretoria; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 2842 (class 0 OID 17478)
-- Dependencies: 258
-- Data for Name: tb_relatorio_gerente; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3167 (class 0 OID 0)
-- Dependencies: 259
-- Name: tb_relatorio_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_relatorio_id_seq', 1, false);


--
-- TOC entry 2844 (class 0 OID 17483)
-- Dependencies: 260
-- Data for Name: tb_relatorio_reiniciar; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 2845 (class 0 OID 17486)
-- Dependencies: 261
-- Data for Name: tb_relatorio_risco_pos; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 2846 (class 0 OID 17489)
-- Dependencies: 262
-- Data for Name: tb_relatorio_setor; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 2847 (class 0 OID 17492)
-- Dependencies: 263
-- Data for Name: tb_relatorio_sureg; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 2848 (class 0 OID 17495)
-- Dependencies: 264
-- Data for Name: tb_resposta; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3168 (class 0 OID 0)
-- Dependencies: 265
-- Name: tb_resposta_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_resposta_id_seq', 1, false);


--
-- TOC entry 2881 (class 0 OID 22019)
-- Dependencies: 299
-- Data for Name: tb_restricao; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--

INSERT INTO tb_restricao VALUES (5, 'admin', 'Admin', 'Admin', 'Admin');
INSERT INTO tb_restricao VALUES (6, 'delete', 'delete', 'delete', 'delete');


--
-- TOC entry 2882 (class 0 OID 22023)
-- Dependencies: 300
-- Data for Name: tb_restricao_modulo_perfil; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--

INSERT INTO tb_restricao_modulo_perfil VALUES (148, 266, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 275, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 280, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 284, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 298, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 289, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 286, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 269, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 302, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 287, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 288, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 305, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 307, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 313, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 308, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 309, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 310, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 311, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 312, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 314, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 315, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 316, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 273, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 318, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 319, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 320, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 275, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 268, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 280, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 284, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 289, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 269, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 302, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 287, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 288, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 317, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 318, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 319, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 290, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 291, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 295, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 293, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (149, 273, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 267, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 274, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 266, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 278, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 276, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 277, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 280, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 271, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 282, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 283, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 285, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 289, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 302, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 324, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 325, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 300, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 326, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 294, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 304, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 288, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 279, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 303, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 273, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 319, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 323, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 296, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 297, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 267, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 274, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 278, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 276, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 277, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 280, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 271, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 282, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 283, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 285, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 289, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 302, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 324, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 325, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 300, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 326, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 294, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 319, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 323, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 296, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (150, 297, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (151, 266, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (151, 270, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (151, 298, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (151, 265, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (151, 316, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (151, 273, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (152, 316, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (152, 273, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (159, 273, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (155, 273, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 267, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 274, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 266, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 278, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 275, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 276, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 277, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 268, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 280, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 271, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 281, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 282, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 283, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 284, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 298, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 285, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 299, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 289, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 286, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 269, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 302, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 287, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 324, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 325, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 300, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 326, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 294, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 288, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 279, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 272, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 303, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 316, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 273, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 317, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 318, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 319, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 290, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 291, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 293, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 295, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 323, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 296, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 297, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 267, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 274, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 266, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 278, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 275, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 276, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 277, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 268, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 280, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 271, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 281, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 282, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 283, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 284, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 298, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 285, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 299, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 289, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 286, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 269, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 302, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 287, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 324, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 325, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 300, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 326, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 294, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 288, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 265, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 272, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 316, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 273, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 317, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 318, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 319, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 290, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 291, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 293, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 295, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 323, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 296, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (160, 297, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (161, 289, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (161, 286, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (161, 302, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (161, 279, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (161, 301, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (161, 316, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (161, 273, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (161, 275, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (161, 284, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (161, 287, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (161, 288, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (161, 275, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (161, 287, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (161, 288, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (161, 284, 6);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 324, 5);
INSERT INTO tb_restricao_modulo_perfil VALUES (148, 326, 5);


--
-- TOC entry 3169 (class 0 OID 0)
-- Dependencies: 298
-- Name: tb_restricao_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_restricao_seq', 1, false);


--
-- TOC entry 2850 (class 0 OID 17503)
-- Dependencies: 266
-- Data for Name: tb_risco_pos; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3170 (class 0 OID 0)
-- Dependencies: 267
-- Name: tb_risco_pos_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_risco_pos_id_seq', 1, false);


--
-- TOC entry 2852 (class 0 OID 17511)
-- Dependencies: 268
-- Data for Name: tb_risco_pre; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3171 (class 0 OID 0)
-- Dependencies: 269
-- Name: tb_risco_pre_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_risco_pre_id_seq', 1, false);


--
-- TOC entry 2883 (class 0 OID 22026)
-- Dependencies: 301
-- Data for Name: tb_sistema; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--

INSERT INTO tb_sistema VALUES (47, 'SIAUDI2', '/siaudi2/index.php', 'Sistema de Auditoria Interna - versão 2.0', '/img/logo_SIAUDI2.jpg', true);


--
-- TOC entry 2854 (class 0 OID 17519)
-- Dependencies: 270
-- Data for Name: tb_subcriterio; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3172 (class 0 OID 0)
-- Dependencies: 271
-- Name: tb_subcriterio_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_subcriterio_id_seq', 1, false);


--
-- TOC entry 2856 (class 0 OID 17524)
-- Dependencies: 272
-- Data for Name: tb_subrisco; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3173 (class 0 OID 0)
-- Dependencies: 273
-- Name: tb_subrisco_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_subrisco_id_seq', 1, false);


--
-- TOC entry 2858 (class 0 OID 17532)
-- Dependencies: 274
-- Data for Name: tb_substituto_regional; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3174 (class 0 OID 0)
-- Dependencies: 275
-- Name: tb_substituto_regional_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_substituto_regional_id_seq', 1, false);


--
-- TOC entry 2860 (class 0 OID 17537)
-- Dependencies: 276
-- Data for Name: tb_sureg; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 2861 (class 0 OID 17540)
-- Dependencies: 277
-- Data for Name: tb_tipo_cliente; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3175 (class 0 OID 0)
-- Dependencies: 278
-- Name: tb_tipo_cliente_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_tipo_cliente_id_seq', 1, false);


--
-- TOC entry 2863 (class 0 OID 17545)
-- Dependencies: 279
-- Data for Name: tb_tipo_criterio; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3176 (class 0 OID 0)
-- Dependencies: 280
-- Name: tb_tipo_criterio_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_tipo_criterio_id_seq', 1, false);


--
-- TOC entry 3177 (class 0 OID 0)
-- Dependencies: 281
-- Name: tb_tipo_diretoria_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_tipo_diretoria_id_seq', 1, false);


--
-- TOC entry 2866 (class 0 OID 17552)
-- Dependencies: 282
-- Data for Name: tb_tipo_processo; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3178 (class 0 OID 0)
-- Dependencies: 283
-- Name: tb_tipo_processo_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_tipo_processo_id_seq', 1, false);


--
-- TOC entry 2868 (class 0 OID 17557)
-- Dependencies: 284
-- Data for Name: tb_tipo_status; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--



--
-- TOC entry 3179 (class 0 OID 0)
-- Dependencies: 285
-- Name: tb_tipo_status_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_tipo_status_id_seq', 1, false);


--
-- TOC entry 2876 (class 0 OID 22005)
-- Dependencies: 294
-- Data for Name: tb_uf; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--

INSERT INTO tb_uf VALUES ('GO', 'Goiás', '52');
INSERT INTO tb_uf VALUES ('AC', 'Acre', '12');
INSERT INTO tb_uf VALUES ('AM', 'Amazonas', '13');
INSERT INTO tb_uf VALUES ('RO', 'Rondônia', '11');
INSERT INTO tb_uf VALUES ('RR', 'Roraima', '14');
INSERT INTO tb_uf VALUES ('TO', 'Tocantins', '17');
INSERT INTO tb_uf VALUES ('MA', 'Maranhão', '21');
INSERT INTO tb_uf VALUES ('AP', 'Amapá', '16');
INSERT INTO tb_uf VALUES ('PA', 'Pará', '15');
INSERT INTO tb_uf VALUES ('SP', 'São Paulo', '35');
INSERT INTO tb_uf VALUES ('RJ', 'Rio de Janeiro', '33');
INSERT INTO tb_uf VALUES ('ES', 'Espírito Santo', '32');
INSERT INTO tb_uf VALUES ('MG', 'Minas Gerais', '31');
INSERT INTO tb_uf VALUES ('PR', 'Paraná', '41');
INSERT INTO tb_uf VALUES ('SC', 'Santa Catarina', '42');
INSERT INTO tb_uf VALUES ('RS', 'Rio Grande do Sul', '43');
INSERT INTO tb_uf VALUES ('DF', 'Distrito Federal', '53');
INSERT INTO tb_uf VALUES ('MT', 'Mato Grosso', '51');
INSERT INTO tb_uf VALUES ('MS', 'Mato Grosso do Sul', '50');
INSERT INTO tb_uf VALUES ('PB', 'Paraíba', '25');
INSERT INTO tb_uf VALUES ('BA', 'Bahia', '29');
INSERT INTO tb_uf VALUES ('AL', 'Alagoas', '27');
INSERT INTO tb_uf VALUES ('CE', 'Ceará', '23');
INSERT INTO tb_uf VALUES ('PE', 'Pernambuco', '26');
INSERT INTO tb_uf VALUES ('RN', 'Rio Grande do Norte', '24');
INSERT INTO tb_uf VALUES ('SE', 'Sergipe', '28');
INSERT INTO tb_uf VALUES ('PI', 'Piauí', '22');


--
-- TOC entry 3180 (class 0 OID 0)
-- Dependencies: 287
-- Name: tb_unidade_administrativa_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_unidade_administrativa_id_seq', 1, true);


--
-- TOC entry 2873 (class 0 OID 17571)
-- Dependencies: 289
-- Data for Name: tb_usuario; Type: TABLE DATA; Schema: siaudi; Owner: usrsiaudi
--

INSERT INTO tb_usuario VALUES ('siaudi.gerente', 2798, 'siaudi.gerente', 160, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'e10adc3949ba59abbe56e057f20f883e');


--
-- TOC entry 3181 (class 0 OID 0)
-- Dependencies: 288
-- Name: tb_usuario_id_seq; Type: SEQUENCE SET; Schema: siaudi; Owner: usrsiaudi
--

SELECT pg_catalog.setval('tb_usuario_id_seq', 25, true);


--
-- TOC entry 2460 (class 2606 OID 17612)
-- Name: acao_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_acao
    ADD CONSTRAINT acao_id PRIMARY KEY (id);


--
-- TOC entry 2527 (class 2606 OID 17614)
-- Name: acesso_item_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_relatorio_acesso_item
    ADD CONSTRAINT acesso_item_id PRIMARY KEY (id);


--
-- TOC entry 2464 (class 2606 OID 17616)
-- Name: avaliacao_criterio_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_avaliacao_criterio
    ADD CONSTRAINT avaliacao_criterio_id PRIMARY KEY (id);


--
-- TOC entry 2462 (class 2606 OID 17618)
-- Name: avaliacao_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_avaliacao
    ADD CONSTRAINT avaliacao_id PRIMARY KEY (id);


--
-- TOC entry 2466 (class 2606 OID 17620)
-- Name: avaliacao_nota_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_avaliacao_nota
    ADD CONSTRAINT avaliacao_nota_id PRIMARY KEY (id);


--
-- TOC entry 2468 (class 2606 OID 17622)
-- Name: avaliacao_observacao_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_avaliacao_observacao
    ADD CONSTRAINT avaliacao_observacao_id PRIMARY KEY (id);


--
-- TOC entry 2474 (class 2606 OID 17624)
-- Name: categoria_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_categoria
    ADD CONSTRAINT categoria_id PRIMARY KEY (id);


--
-- TOC entry 2486 (class 2606 OID 17626)
-- Name: conclusao_homens_hora_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_homens_hora
    ADD CONSTRAINT conclusao_homens_hora_id PRIMARY KEY (id);


--
-- TOC entry 2476 (class 2606 OID 17628)
-- Name: criterio_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_criterio
    ADD CONSTRAINT criterio_id PRIMARY KEY (id);


--
-- TOC entry 2480 (class 2606 OID 17630)
-- Name: especie_auditoria_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_especie_auditoria
    ADD CONSTRAINT especie_auditoria_id PRIMARY KEY (id);


--
-- TOC entry 2482 (class 2606 OID 17632)
-- Name: feriado_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_feriado
    ADD CONSTRAINT feriado_id PRIMARY KEY (id);


--
-- TOC entry 2500 (class 2606 OID 17634)
-- Name: item_objeto_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_objeto
    ADD CONSTRAINT item_objeto_id PRIMARY KEY (id);


--
-- TOC entry 2498 (class 2606 OID 17636)
-- Name: local_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_nucleo
    ADD CONSTRAINT local_id PRIMARY KEY (id);


--
-- TOC entry 2496 (class 2606 OID 17638)
-- Name: manifestacao_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_manifestacao
    ADD CONSTRAINT manifestacao_id PRIMARY KEY (id);


--
-- TOC entry 2502 (class 2606 OID 17640)
-- Name: paint_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_paint
    ADD CONSTRAINT paint_id PRIMARY KEY (id);


--
-- TOC entry 2568 (class 2606 OID 22034)
-- Name: pk_restricao_modulo_perfil_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_restricao_modulo_perfil
    ADD CONSTRAINT pk_restricao_modulo_perfil_id PRIMARY KEY (perfil_fk, modulo_fk, restricao_fk);


--
-- TOC entry 2490 (class 2606 OID 17642)
-- Name: pk_tb_imagem; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_imagem
    ADD CONSTRAINT pk_tb_imagem PRIMARY KEY (id);


--
-- TOC entry 2558 (class 2606 OID 22036)
-- Name: pk_tb_menu; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_menu
    ADD CONSTRAINT pk_tb_menu PRIMARY KEY (id);


--
-- TOC entry 2562 (class 2606 OID 22038)
-- Name: pk_tb_menu_perfil; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_menu_perfil
    ADD CONSTRAINT pk_tb_menu_perfil PRIMARY KEY (perfil_fk, menu_fk);


--
-- TOC entry 2564 (class 2606 OID 22040)
-- Name: pk_tb_modulo; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_modulo
    ADD CONSTRAINT pk_tb_modulo PRIMARY KEY (id);


--
-- TOC entry 2566 (class 2606 OID 22042)
-- Name: pk_tb_restricao; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_restricao
    ADD CONSTRAINT pk_tb_restricao PRIMARY KEY (id);


--
-- TOC entry 2556 (class 2606 OID 17644)
-- Name: plan_auditor_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_usuario
    ADD CONSTRAINT plan_auditor_id PRIMARY KEY (id);


--
-- TOC entry 2507 (class 2606 OID 17646)
-- Name: plan_especifico_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_plan_especifico
    ADD CONSTRAINT plan_especifico_id PRIMARY KEY (id);


--
-- TOC entry 2509 (class 2606 OID 17648)
-- Name: processo_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_processo
    ADD CONSTRAINT processo_id PRIMARY KEY (id);


--
-- TOC entry 2511 (class 2606 OID 17650)
-- Name: raint_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_raint
    ADD CONSTRAINT raint_id PRIMARY KEY (id);


--
-- TOC entry 2515 (class 2606 OID 17652)
-- Name: recomendacao_categoria_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_recomendacao_categoria
    ADD CONSTRAINT recomendacao_categoria_id PRIMARY KEY (id);


--
-- TOC entry 2517 (class 2606 OID 17654)
-- Name: recomendacao_gravidade_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_recomendacao_gravidade
    ADD CONSTRAINT recomendacao_gravidade_id PRIMARY KEY (id);


--
-- TOC entry 2519 (class 2606 OID 17656)
-- Name: recomendacao_padrao_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_recomendacao_padrao
    ADD CONSTRAINT recomendacao_padrao_id PRIMARY KEY (id);


--
-- TOC entry 2521 (class 2606 OID 17658)
-- Name: recomendacao_subcategoria_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_recomendacao_subcategoria
    ADD CONSTRAINT recomendacao_subcategoria_id PRIMARY KEY (id);


--
-- TOC entry 2523 (class 2606 OID 17660)
-- Name: recomendacao_tipo_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_recomendacao_tipo
    ADD CONSTRAINT recomendacao_tipo_id PRIMARY KEY (id);


--
-- TOC entry 2529 (class 2606 OID 17662)
-- Name: relatorio_cabecalho_rodape_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_relatorio_despacho
    ADD CONSTRAINT relatorio_cabecalho_rodape_id PRIMARY KEY (id);


--
-- TOC entry 2525 (class 2606 OID 17664)
-- Name: relatorio_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_relatorio
    ADD CONSTRAINT relatorio_id PRIMARY KEY (id);


--
-- TOC entry 2532 (class 2606 OID 17666)
-- Name: resposta_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_resposta
    ADD CONSTRAINT resposta_id PRIMARY KEY (id);


--
-- TOC entry 2534 (class 2606 OID 17668)
-- Name: risco_pos_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_risco_pos
    ADD CONSTRAINT risco_pos_id PRIMARY KEY (id);


--
-- TOC entry 2536 (class 2606 OID 17670)
-- Name: risco_pre_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_risco_pre
    ADD CONSTRAINT risco_pre_id PRIMARY KEY (id);


--
-- TOC entry 2538 (class 2606 OID 17672)
-- Name: subcriterio_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_subcriterio
    ADD CONSTRAINT subcriterio_id PRIMARY KEY (id);


--
-- TOC entry 2540 (class 2606 OID 17674)
-- Name: subrisco_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_subrisco
    ADD CONSTRAINT subrisco_id PRIMARY KEY (id);


--
-- TOC entry 2542 (class 2606 OID 17676)
-- Name: substituto_regional_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_substituto_regional
    ADD CONSTRAINT substituto_regional_id PRIMARY KEY (id);


--
-- TOC entry 2470 (class 2606 OID 17678)
-- Name: tb_capitulo_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_capitulo
    ADD CONSTRAINT tb_capitulo_id PRIMARY KEY (id);


--
-- TOC entry 2472 (class 2606 OID 17680)
-- Name: tb_cargo__id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_cargo
    ADD CONSTRAINT tb_cargo__id PRIMARY KEY (id);


--
-- TOC entry 2505 (class 2606 OID 17682)
-- Name: tb_cargo_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_perfil
    ADD CONSTRAINT tb_cargo_id PRIMARY KEY (id);


--
-- TOC entry 2484 (class 2606 OID 17684)
-- Name: tb_funcao_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_funcao
    ADD CONSTRAINT tb_funcao_id PRIMARY KEY (id);


--
-- TOC entry 2488 (class 2606 OID 17686)
-- Name: tb_homens_hora_conf_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_homens_hora_conf
    ADD CONSTRAINT tb_homens_hora_conf_id PRIMARY KEY (id);


--
-- TOC entry 2492 (class 2606 OID 17688)
-- Name: tb_item_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_item
    ADD CONSTRAINT tb_item_id PRIMARY KEY (id);


--
-- TOC entry 2494 (class 2606 OID 17690)
-- Name: tb_log_entrada_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_log_entrada
    ADD CONSTRAINT tb_log_entrada_id PRIMARY KEY (id);


--
-- TOC entry 2513 (class 2606 OID 17692)
-- Name: tb_recomendacao_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_recomendacao
    ADD CONSTRAINT tb_recomendacao_id PRIMARY KEY (id);


--
-- TOC entry 2570 (class 2606 OID 22044)
-- Name: tb_sistema_pkey; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_sistema
    ADD CONSTRAINT tb_sistema_pkey PRIMARY KEY (id);


--
-- TOC entry 2544 (class 2606 OID 17694)
-- Name: tb_sureg_pk; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_sureg
    ADD CONSTRAINT tb_sureg_pk PRIMARY KEY (id);


--
-- TOC entry 2560 (class 2606 OID 22046)
-- Name: tb_uf_pkey; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_uf
    ADD CONSTRAINT tb_uf_pkey PRIMARY KEY (sigla);


--
-- TOC entry 2554 (class 2606 OID 17696)
-- Name: tb_unidade_administrativa_pk; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_unidade_administrativa
    ADD CONSTRAINT tb_unidade_administrativa_pk PRIMARY KEY (id);


--
-- TOC entry 2546 (class 2606 OID 17698)
-- Name: tipo_cliente_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_tipo_cliente
    ADD CONSTRAINT tipo_cliente_id PRIMARY KEY (id);


--
-- TOC entry 2548 (class 2606 OID 17700)
-- Name: tipo_criterio_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_tipo_criterio
    ADD CONSTRAINT tipo_criterio_id PRIMARY KEY (id);


--
-- TOC entry 2478 (class 2606 OID 17702)
-- Name: tipo_diretoria_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_diretoria
    ADD CONSTRAINT tipo_diretoria_id PRIMARY KEY (id);


--
-- TOC entry 2550 (class 2606 OID 17704)
-- Name: tipo_processo_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_tipo_processo
    ADD CONSTRAINT tipo_processo_id PRIMARY KEY (id);


--
-- TOC entry 2552 (class 2606 OID 17706)
-- Name: tipo_status_id; Type: CONSTRAINT; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

ALTER TABLE ONLY tb_tipo_status
    ADD CONSTRAINT tipo_status_id PRIMARY KEY (id);


--
-- TOC entry 2503 (class 1259 OID 22047)
-- Name: ix_tb_perfil_sistema_fk; Type: INDEX; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE INDEX ix_tb_perfil_sistema_fk ON tb_perfil USING btree (sistema_fk);


--
-- TOC entry 2530 (class 1259 OID 17707)
-- Name: ix_tb_unidade_auditada; Type: INDEX; Schema: siaudi; Owner: usrsiaudi; Tablespace: 
--

CREATE UNIQUE INDEX ix_tb_unidade_auditada ON tb_relatorio_sureg USING btree (relatorio_fk, unidade_administrativa_fk);


--
-- TOC entry 2583 (class 2606 OID 17708)
-- Name: fk_tb_auditor_tb_homens_hora; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_homens_hora
    ADD CONSTRAINT fk_tb_auditor_tb_homens_hora FOREIGN KEY (usuario_fk) REFERENCES tb_usuario(id);


--
-- TOC entry 2586 (class 2606 OID 17713)
-- Name: fk_tb_manifestacao_tb_relatorio; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_manifestacao
    ADD CONSTRAINT fk_tb_manifestacao_tb_relatorio FOREIGN KEY (relatorio_fk) REFERENCES tb_relatorio(id);


--
-- TOC entry 2631 (class 2606 OID 22048)
-- Name: fk_tb_menu_pai; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_menu
    ADD CONSTRAINT fk_tb_menu_pai FOREIGN KEY (menu_pai_fk) REFERENCES tb_menu(id);


--
-- TOC entry 2634 (class 2606 OID 22053)
-- Name: fk_tb_menu_perfil_tb_menu; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_menu_perfil
    ADD CONSTRAINT fk_tb_menu_perfil_tb_menu FOREIGN KEY (menu_fk) REFERENCES tb_menu(id);


--
-- TOC entry 2635 (class 2606 OID 22058)
-- Name: fk_tb_menu_perfil_tb_perfil; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_menu_perfil
    ADD CONSTRAINT fk_tb_menu_perfil_tb_perfil FOREIGN KEY (perfil_fk) REFERENCES tb_perfil(id);


--
-- TOC entry 2609 (class 2606 OID 17718)
-- Name: fk_tb_relatorio_area_tb_relatorio; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_relatorio_area
    ADD CONSTRAINT fk_tb_relatorio_area_tb_relatorio FOREIGN KEY (relatorio_fk) REFERENCES tb_relatorio(id);


--
-- TOC entry 2610 (class 2606 OID 17723)
-- Name: fk_tb_relatorio_area_tb_unidade_administrativa; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_relatorio_area
    ADD CONSTRAINT fk_tb_relatorio_area_tb_unidade_administrativa FOREIGN KEY (unidade_administrativa_fk) REFERENCES tb_unidade_administrativa(id);


--
-- TOC entry 2611 (class 2606 OID 17728)
-- Name: fk_tb_relatorio_auditor_tb_auditor; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_relatorio_auditor
    ADD CONSTRAINT fk_tb_relatorio_auditor_tb_auditor FOREIGN KEY (usuario_fk) REFERENCES tb_usuario(id);


--
-- TOC entry 2612 (class 2606 OID 17733)
-- Name: fk_tb_relatorio_auditor_tb_relatorio; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_relatorio_auditor
    ADD CONSTRAINT fk_tb_relatorio_auditor_tb_relatorio FOREIGN KEY (relatorio_fk) REFERENCES tb_relatorio(id);


--
-- TOC entry 2613 (class 2606 OID 17738)
-- Name: fk_tb_relatorio_diretoria_tb_relatorio; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_relatorio_diretoria
    ADD CONSTRAINT fk_tb_relatorio_diretoria_tb_relatorio FOREIGN KEY (relatorio_fk) REFERENCES tb_relatorio(id);


--
-- TOC entry 2615 (class 2606 OID 17743)
-- Name: fk_tb_relatorio_gerente_tb_auditor; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_relatorio_gerente
    ADD CONSTRAINT fk_tb_relatorio_gerente_tb_auditor FOREIGN KEY (usuario_fk) REFERENCES tb_usuario(id);


--
-- TOC entry 2616 (class 2606 OID 17748)
-- Name: fk_tb_relatorio_gerente_tb_relatorio; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_relatorio_gerente
    ADD CONSTRAINT fk_tb_relatorio_gerente_tb_relatorio FOREIGN KEY (relatorio_fk) REFERENCES tb_relatorio(id);


--
-- TOC entry 2619 (class 2606 OID 17753)
-- Name: fk_tb_relatorio_setor_tb_relatorio; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_relatorio_setor
    ADD CONSTRAINT fk_tb_relatorio_setor_tb_relatorio FOREIGN KEY (relatorio_fk) REFERENCES tb_relatorio(id);


--
-- TOC entry 2620 (class 2606 OID 17758)
-- Name: fk_tb_relatorio_setor_tb_unidade_administrativa; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_relatorio_setor
    ADD CONSTRAINT fk_tb_relatorio_setor_tb_unidade_administrativa FOREIGN KEY (unidade_administrativa_fk) REFERENCES tb_unidade_administrativa(id);


--
-- TOC entry 2621 (class 2606 OID 17763)
-- Name: fk_tb_relatorio_sureg_tb_relatorio; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_relatorio_sureg
    ADD CONSTRAINT fk_tb_relatorio_sureg_tb_relatorio FOREIGN KEY (relatorio_fk) REFERENCES tb_relatorio(id);


--
-- TOC entry 2604 (class 2606 OID 17768)
-- Name: fk_tb_relatorio_tb_categoria; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_relatorio
    ADD CONSTRAINT fk_tb_relatorio_tb_categoria FOREIGN KEY (categoria_fk) REFERENCES tb_categoria(id);


--
-- TOC entry 2605 (class 2606 OID 17773)
-- Name: fk_tb_relatorio_tb_especie_auditoria; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_relatorio
    ADD CONSTRAINT fk_tb_relatorio_tb_especie_auditoria FOREIGN KEY (especie_auditoria_fk) REFERENCES tb_especie_auditoria(id);


--
-- TOC entry 2622 (class 2606 OID 17778)
-- Name: fk_tb_resposta_tb_tipo_status; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_resposta
    ADD CONSTRAINT fk_tb_resposta_tb_tipo_status FOREIGN KEY (tipo_status_fk) REFERENCES tb_tipo_status(id);


--
-- TOC entry 2626 (class 2606 OID 17783)
-- Name: fk_tb_usuario_cargo_fk; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_usuario
    ADD CONSTRAINT fk_tb_usuario_cargo_fk FOREIGN KEY (cargo_fk) REFERENCES tb_cargo(id) ON UPDATE CASCADE;


--
-- TOC entry 2627 (class 2606 OID 17788)
-- Name: fk_tb_usuario_funcao_fk; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_usuario
    ADD CONSTRAINT fk_tb_usuario_funcao_fk FOREIGN KEY (funcao_fk) REFERENCES tb_funcao(id);


--
-- TOC entry 2628 (class 2606 OID 17793)
-- Name: fk_tb_usuario_unidade_administrativa_fk; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_usuario
    ADD CONSTRAINT fk_tb_usuario_unidade_administrativa_fk FOREIGN KEY (unidade_administrativa_fk) REFERENCES tb_unidade_administrativa(id) ON UPDATE CASCADE;


--
-- TOC entry 2632 (class 2606 OID 22063)
-- Name: fka4fb94905186a848; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_menu
    ADD CONSTRAINT fka4fb94905186a848 FOREIGN KEY (id) REFERENCES tb_menu(id);


--
-- TOC entry 2633 (class 2606 OID 22068)
-- Name: fka4fb94909cbe5ff8; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_menu
    ADD CONSTRAINT fka4fb94909cbe5ff8 FOREIGN KEY (sistema_fk) REFERENCES tb_sistema(id);


--
-- TOC entry 2573 (class 2606 OID 17798)
-- Name: tb_acao_tb_acao_mes_fk; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_acao_mes
    ADD CONSTRAINT tb_acao_tb_acao_mes_fk FOREIGN KEY (acao_fk) REFERENCES tb_acao(id);


--
-- TOC entry 2574 (class 2606 OID 17803)
-- Name: tb_acao_tb_acao_risco_pre_fk; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_acao_risco_pre
    ADD CONSTRAINT tb_acao_tb_acao_risco_pre_fk FOREIGN KEY (acao_fk) REFERENCES tb_acao(id);


--
-- TOC entry 2575 (class 2606 OID 17808)
-- Name: tb_acao_tb_acao_sureg_fk; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_acao_sureg
    ADD CONSTRAINT tb_acao_tb_acao_sureg_fk FOREIGN KEY (acao_fk) REFERENCES tb_acao(id);


--
-- TOC entry 2607 (class 2606 OID 17813)
-- Name: tb_acesso_relatorio_tb_relatorio; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_relatorio_acesso
    ADD CONSTRAINT tb_acesso_relatorio_tb_relatorio FOREIGN KEY (relatorio_fk) REFERENCES tb_relatorio(id);


--
-- TOC entry 2578 (class 2606 OID 17818)
-- Name: tb_avaliacao_nota_tb_avaliacao; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_avaliacao_nota
    ADD CONSTRAINT tb_avaliacao_nota_tb_avaliacao FOREIGN KEY (avaliacao_fk) REFERENCES tb_avaliacao(id);


--
-- TOC entry 2580 (class 2606 OID 17823)
-- Name: tb_avaliacao_observacao_tb_avaliacao; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_avaliacao_observacao
    ADD CONSTRAINT tb_avaliacao_observacao_tb_avaliacao FOREIGN KEY (avaliacao_fk) REFERENCES tb_avaliacao(id);


--
-- TOC entry 2579 (class 2606 OID 17828)
-- Name: tb_avaliacao_tb_nota_avaliacao_criterio; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_avaliacao_nota
    ADD CONSTRAINT tb_avaliacao_tb_nota_avaliacao_criterio FOREIGN KEY (avaliacao_criterio_fk) REFERENCES tb_avaliacao_criterio(id);


--
-- TOC entry 2576 (class 2606 OID 17833)
-- Name: tb_avaliacao_tb_relatorio; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_avaliacao
    ADD CONSTRAINT tb_avaliacao_tb_relatorio FOREIGN KEY (relatorio_fk) REFERENCES tb_relatorio(id);


--
-- TOC entry 2577 (class 2606 OID 17838)
-- Name: tb_avaliacao_tb_usuario; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_avaliacao
    ADD CONSTRAINT tb_avaliacao_tb_usuario FOREIGN KEY (usuario_fk) REFERENCES tb_usuario(id);


--
-- TOC entry 2623 (class 2606 OID 17843)
-- Name: tb_criterio_tb_subcriterio_fk; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_subcriterio
    ADD CONSTRAINT tb_criterio_tb_subcriterio_fk FOREIGN KEY (criterio_fk) REFERENCES tb_criterio(id);


--
-- TOC entry 2571 (class 2606 OID 17848)
-- Name: tb_especie_auditoria_tb_acao_fk; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_acao
    ADD CONSTRAINT tb_especie_auditoria_tb_acao_fk FOREIGN KEY (especie_auditoria_fk) REFERENCES tb_especie_auditoria(id);


--
-- TOC entry 2593 (class 2606 OID 17853)
-- Name: tb_especie_auditoria_tb_processo_especie_auditoria_fk; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_processo_especie_auditoria
    ADD CONSTRAINT tb_especie_auditoria_tb_processo_especie_auditoria_fk FOREIGN KEY (especie_auditoria_fk) REFERENCES tb_especie_auditoria(id);


--
-- TOC entry 2584 (class 2606 OID 17858)
-- Name: tb_item_tb_capitulo; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_item
    ADD CONSTRAINT tb_item_tb_capitulo FOREIGN KEY (capitulo_fk) REFERENCES tb_capitulo(id);


--
-- TOC entry 2585 (class 2606 OID 17863)
-- Name: tb_item_tb_objeto; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_item
    ADD CONSTRAINT tb_item_tb_objeto FOREIGN KEY (objeto_fk) REFERENCES tb_objeto(id);


--
-- TOC entry 2587 (class 2606 OID 17868)
-- Name: tb_paint_tb_paint_fk; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_paint
    ADD CONSTRAINT tb_paint_tb_paint_fk FOREIGN KEY (numero_item_pai) REFERENCES tb_paint(id);


--
-- TOC entry 2588 (class 2606 OID 22073)
-- Name: tb_perfil_sistema_fkc; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_perfil
    ADD CONSTRAINT tb_perfil_sistema_fkc FOREIGN KEY (sistema_fk) REFERENCES tb_sistema(id);


--
-- TOC entry 2589 (class 2606 OID 17873)
-- Name: tb_plan_especifico_tb_plano_acao_fk; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_plan_especifico
    ADD CONSTRAINT tb_plan_especifico_tb_plano_acao_fk FOREIGN KEY (acao_fk) REFERENCES tb_acao(id);


--
-- TOC entry 2591 (class 2606 OID 17878)
-- Name: tb_plan_especifico_tb_plano_especifico_auditores_fk; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_plan_especifico_auditor
    ADD CONSTRAINT tb_plan_especifico_tb_plano_especifico_auditores_fk FOREIGN KEY (plan_especifico_fk) REFERENCES tb_plan_especifico(id);


--
-- TOC entry 2572 (class 2606 OID 17883)
-- Name: tb_processo_tb_acao_fk2; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_acao
    ADD CONSTRAINT tb_processo_tb_acao_fk2 FOREIGN KEY (processo_fk) REFERENCES tb_processo(id);


--
-- TOC entry 2594 (class 2606 OID 17888)
-- Name: tb_processo_tb_processo_especie_auditoria_fk; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_processo_especie_auditoria
    ADD CONSTRAINT tb_processo_tb_processo_especie_auditoria_fk FOREIGN KEY (processo_fk) REFERENCES tb_processo(id);


--
-- TOC entry 2595 (class 2606 OID 17893)
-- Name: tb_processo_tb_processo_risco_pre_fk; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_processo_risco_pre
    ADD CONSTRAINT tb_processo_tb_processo_risco_pre_fk FOREIGN KEY (processo_fk) REFERENCES tb_processo(id);


--
-- TOC entry 2624 (class 2606 OID 17898)
-- Name: tb_processo_tb_subrisco_fk; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_subrisco
    ADD CONSTRAINT tb_processo_tb_subrisco_fk FOREIGN KEY (processo_fk) REFERENCES tb_processo(id);


--
-- TOC entry 2597 (class 2606 OID 17903)
-- Name: tb_raint_tb_raint_fk; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_raint
    ADD CONSTRAINT tb_raint_tb_raint_fk FOREIGN KEY (numero_item_pai) REFERENCES tb_raint(id);


--
-- TOC entry 2603 (class 2606 OID 17908)
-- Name: tb_recomendacao_subcategoria_tb_recomendacao_categoria_fk; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_recomendacao_subcategoria
    ADD CONSTRAINT tb_recomendacao_subcategoria_tb_recomendacao_categoria_fk FOREIGN KEY (recomendacao_categoria_fk) REFERENCES tb_recomendacao_categoria(id);


--
-- TOC entry 2598 (class 2606 OID 17913)
-- Name: tb_recomendacao_tb_item; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_recomendacao
    ADD CONSTRAINT tb_recomendacao_tb_item FOREIGN KEY (item_fk) REFERENCES tb_item(id);


--
-- TOC entry 2599 (class 2606 OID 17918)
-- Name: tb_recomendacao_tb_recomendacao_categoria; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_recomendacao
    ADD CONSTRAINT tb_recomendacao_tb_recomendacao_categoria FOREIGN KEY (recomendacao_categoria_fk) REFERENCES tb_recomendacao_categoria(id);


--
-- TOC entry 2600 (class 2606 OID 17923)
-- Name: tb_recomendacao_tb_recomendacao_gravidade; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_recomendacao
    ADD CONSTRAINT tb_recomendacao_tb_recomendacao_gravidade FOREIGN KEY (recomendacao_gravidade_fk) REFERENCES tb_recomendacao_gravidade(id);


--
-- TOC entry 2601 (class 2606 OID 17928)
-- Name: tb_recomendacao_tb_recomendacao_subcategoria; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_recomendacao
    ADD CONSTRAINT tb_recomendacao_tb_recomendacao_subcategoria FOREIGN KEY (recomendacao_subcategoria_fk) REFERENCES tb_recomendacao_subcategoria(id);


--
-- TOC entry 2602 (class 2606 OID 17933)
-- Name: tb_recomendacao_tb_recomendacao_tipo; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_recomendacao
    ADD CONSTRAINT tb_recomendacao_tb_recomendacao_tipo FOREIGN KEY (recomendacao_tipo_fk) REFERENCES tb_recomendacao_tipo(id);


--
-- TOC entry 2608 (class 2606 OID 17938)
-- Name: tb_relatorio_acesso_item_tb_item; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_relatorio_acesso_item
    ADD CONSTRAINT tb_relatorio_acesso_item_tb_item FOREIGN KEY (item_fk) REFERENCES tb_item(id);


--
-- TOC entry 2614 (class 2606 OID 17943)
-- Name: tb_relatorio_diretoria_diretoria_fk_fkey; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_relatorio_diretoria
    ADD CONSTRAINT tb_relatorio_diretoria_diretoria_fk_fkey FOREIGN KEY (diretoria_fk) REFERENCES tb_unidade_administrativa(id);


--
-- TOC entry 2581 (class 2606 OID 17948)
-- Name: tb_relatorio_tb_capitulo_relatorio; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_capitulo
    ADD CONSTRAINT tb_relatorio_tb_capitulo_relatorio FOREIGN KEY (relatorio_fk) REFERENCES tb_relatorio(id);


--
-- TOC entry 2590 (class 2606 OID 17953)
-- Name: tb_relatorio_tb_objeto; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_plan_especifico
    ADD CONSTRAINT tb_relatorio_tb_objeto FOREIGN KEY (objeto_fk) REFERENCES tb_objeto(id);


--
-- TOC entry 2606 (class 2606 OID 17958)
-- Name: tb_relatorio_tb_plan_especifico; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_relatorio
    ADD CONSTRAINT tb_relatorio_tb_plan_especifico FOREIGN KEY (plan_especifico_fk) REFERENCES tb_plan_especifico(id);


--
-- TOC entry 2617 (class 2606 OID 17963)
-- Name: tb_relatorio_tb_relatorio_risco_pos_fk; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_relatorio_risco_pos
    ADD CONSTRAINT tb_relatorio_tb_relatorio_risco_pos_fk FOREIGN KEY (relatorio_fk) REFERENCES tb_relatorio(id);


--
-- TOC entry 2637 (class 2606 OID 22078)
-- Name: tb_restr_mod_perf_tb_perfil_fk; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_restricao_modulo_perfil
    ADD CONSTRAINT tb_restr_mod_perf_tb_perfil_fk FOREIGN KEY (perfil_fk) REFERENCES tb_perfil(id) ON UPDATE CASCADE;


--
-- TOC entry 2638 (class 2606 OID 22083)
-- Name: tb_restr_mod_perf_tb_programa_fk; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_restricao_modulo_perfil
    ADD CONSTRAINT tb_restr_mod_perf_tb_programa_fk FOREIGN KEY (modulo_fk) REFERENCES tb_modulo(id) ON UPDATE CASCADE;


--
-- TOC entry 2639 (class 2606 OID 22088)
-- Name: tb_restr_mod_perf_tb_restricao_fk; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_restricao_modulo_perfil
    ADD CONSTRAINT tb_restr_mod_perf_tb_restricao_fk FOREIGN KEY (restricao_fk) REFERENCES tb_restricao(id) ON UPDATE CASCADE;


--
-- TOC entry 2618 (class 2606 OID 17968)
-- Name: tb_risco_pos_tb_acao_risco_pos_fk; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_relatorio_risco_pos
    ADD CONSTRAINT tb_risco_pos_tb_acao_risco_pos_fk FOREIGN KEY (risco_pos_fk) REFERENCES tb_risco_pos(id);


--
-- TOC entry 2596 (class 2606 OID 17973)
-- Name: tb_risco_pre_tb_acao_risco_pre_fk; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_processo_risco_pre
    ADD CONSTRAINT tb_risco_pre_tb_acao_risco_pre_fk FOREIGN KEY (risco_pre_fk) REFERENCES tb_risco_pre(id);


--
-- TOC entry 2636 (class 2606 OID 22093)
-- Name: tb_sistema_tb_modulo_fk; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_modulo
    ADD CONSTRAINT tb_sistema_tb_modulo_fk FOREIGN KEY (sistema_fk) REFERENCES tb_sistema(id);


--
-- TOC entry 2625 (class 2606 OID 17978)
-- Name: tb_subcriterio_tb_subrisco_fk; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_subrisco
    ADD CONSTRAINT tb_subcriterio_tb_subrisco_fk FOREIGN KEY (subcriterio_fk) REFERENCES tb_subcriterio(id);


--
-- TOC entry 2582 (class 2606 OID 17983)
-- Name: tb_tipo_criterio_tb_criterio_fk; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_criterio
    ADD CONSTRAINT tb_tipo_criterio_tb_criterio_fk FOREIGN KEY (tipo_criterio_fk) REFERENCES tb_tipo_criterio(id);


--
-- TOC entry 2592 (class 2606 OID 17988)
-- Name: tb_tipo_processo_tb_processo_fk; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_processo
    ADD CONSTRAINT tb_tipo_processo_tb_processo_fk FOREIGN KEY (tipo_processo_fk) REFERENCES tb_tipo_processo(id);


--
-- TOC entry 2629 (class 2606 OID 17993)
-- Name: tb_usuario_nucleo_fk; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_usuario
    ADD CONSTRAINT tb_usuario_nucleo_fk FOREIGN KEY (nucleo_fk) REFERENCES tb_nucleo(id);


--
-- TOC entry 2630 (class 2606 OID 17998)
-- Name: tb_usuario_perfil_fk; Type: FK CONSTRAINT; Schema: siaudi; Owner: usrsiaudi
--

ALTER TABLE ONLY tb_usuario
    ADD CONSTRAINT tb_usuario_perfil_fk FOREIGN KEY (perfil_fk) REFERENCES tb_perfil(id);


--
-- TOC entry 2888 (class 0 OID 0)
-- Dependencies: 7
-- Name: siaudi; Type: ACL; Schema: -; Owner: usrsiaudi
--

REVOKE ALL ON SCHEMA siaudi FROM PUBLIC;
REVOKE ALL ON SCHEMA siaudi FROM usrsiaudi;
GRANT ALL ON SCHEMA siaudi TO usrsiaudi;


--
-- TOC entry 2889 (class 0 OID 0)
-- Dependencies: 171
-- Name: raint_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE raint_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE raint_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE raint_id_seq TO usrsiaudi;


--
-- TOC entry 2891 (class 0 OID 0)
-- Dependencies: 172
-- Name: tb_acao; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_acao FROM PUBLIC;
REVOKE ALL ON TABLE tb_acao FROM usrsiaudi;
GRANT ALL ON TABLE tb_acao TO usrsiaudi;


--
-- TOC entry 2893 (class 0 OID 0)
-- Dependencies: 173
-- Name: tb_acao_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_acao_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_acao_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_acao_id_seq TO usrsiaudi;


--
-- TOC entry 2895 (class 0 OID 0)
-- Dependencies: 174
-- Name: tb_acao_mes; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_acao_mes FROM PUBLIC;
REVOKE ALL ON TABLE tb_acao_mes FROM usrsiaudi;
GRANT ALL ON TABLE tb_acao_mes TO usrsiaudi;


--
-- TOC entry 2897 (class 0 OID 0)
-- Dependencies: 175
-- Name: tb_acao_risco_pre; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_acao_risco_pre FROM PUBLIC;
REVOKE ALL ON TABLE tb_acao_risco_pre FROM usrsiaudi;
GRANT ALL ON TABLE tb_acao_risco_pre TO usrsiaudi;


--
-- TOC entry 2899 (class 0 OID 0)
-- Dependencies: 176
-- Name: tb_acao_sureg; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_acao_sureg FROM PUBLIC;
REVOKE ALL ON TABLE tb_acao_sureg FROM usrsiaudi;
GRANT ALL ON TABLE tb_acao_sureg TO usrsiaudi;


--
-- TOC entry 2900 (class 0 OID 0)
-- Dependencies: 177
-- Name: tb_avaliacao_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_avaliacao_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_avaliacao_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_avaliacao_id_seq TO usrsiaudi;


--
-- TOC entry 2902 (class 0 OID 0)
-- Dependencies: 178
-- Name: tb_avaliacao; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_avaliacao FROM PUBLIC;
REVOKE ALL ON TABLE tb_avaliacao FROM usrsiaudi;
GRANT ALL ON TABLE tb_avaliacao TO usrsiaudi;


--
-- TOC entry 2904 (class 0 OID 0)
-- Dependencies: 179
-- Name: tb_avaliacao_auditor_fk_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_avaliacao_auditor_fk_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_avaliacao_auditor_fk_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_avaliacao_auditor_fk_seq TO usrsiaudi;


--
-- TOC entry 2905 (class 0 OID 0)
-- Dependencies: 180
-- Name: tb_avaliacao_criterio_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_avaliacao_criterio_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_avaliacao_criterio_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_avaliacao_criterio_id_seq TO usrsiaudi;


--
-- TOC entry 2906 (class 0 OID 0)
-- Dependencies: 181
-- Name: tb_avaliacao_criterio; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_avaliacao_criterio FROM PUBLIC;
REVOKE ALL ON TABLE tb_avaliacao_criterio FROM usrsiaudi;
GRANT ALL ON TABLE tb_avaliacao_criterio TO usrsiaudi;


--
-- TOC entry 2907 (class 0 OID 0)
-- Dependencies: 182
-- Name: tb_avaliacao_nota_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_avaliacao_nota_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_avaliacao_nota_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_avaliacao_nota_id_seq TO usrsiaudi;


--
-- TOC entry 2909 (class 0 OID 0)
-- Dependencies: 183
-- Name: tb_avaliacao_nota; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_avaliacao_nota FROM PUBLIC;
REVOKE ALL ON TABLE tb_avaliacao_nota FROM usrsiaudi;
GRANT ALL ON TABLE tb_avaliacao_nota TO usrsiaudi;


--
-- TOC entry 2910 (class 0 OID 0)
-- Dependencies: 184
-- Name: tb_avaliacao_observacao_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_avaliacao_observacao_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_avaliacao_observacao_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_avaliacao_observacao_id_seq TO usrsiaudi;


--
-- TOC entry 2912 (class 0 OID 0)
-- Dependencies: 185
-- Name: tb_avaliacao_observacao; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_avaliacao_observacao FROM PUBLIC;
REVOKE ALL ON TABLE tb_avaliacao_observacao FROM usrsiaudi;
GRANT ALL ON TABLE tb_avaliacao_observacao TO usrsiaudi;


--
-- TOC entry 2914 (class 0 OID 0)
-- Dependencies: 186
-- Name: tb_avaliacao_relatorio_fk_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_avaliacao_relatorio_fk_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_avaliacao_relatorio_fk_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_avaliacao_relatorio_fk_seq TO usrsiaudi;


--
-- TOC entry 2916 (class 0 OID 0)
-- Dependencies: 187
-- Name: tb_avaliacao_sureg_fk_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_avaliacao_sureg_fk_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_avaliacao_sureg_fk_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_avaliacao_sureg_fk_seq TO usrsiaudi;


--
-- TOC entry 2917 (class 0 OID 0)
-- Dependencies: 188
-- Name: tb_capitulo_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_capitulo_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_capitulo_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_capitulo_id_seq TO usrsiaudi;


--
-- TOC entry 2918 (class 0 OID 0)
-- Dependencies: 189
-- Name: tb_capitulo; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_capitulo FROM PUBLIC;
REVOKE ALL ON TABLE tb_capitulo FROM usrsiaudi;
GRANT ALL ON TABLE tb_capitulo TO usrsiaudi;


--
-- TOC entry 2919 (class 0 OID 0)
-- Dependencies: 190
-- Name: tb_cargo; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_cargo FROM PUBLIC;
REVOKE ALL ON TABLE tb_cargo FROM usrsiaudi;
GRANT ALL ON TABLE tb_cargo TO usrsiaudi;


--
-- TOC entry 2921 (class 0 OID 0)
-- Dependencies: 191
-- Name: tb_cargo_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_cargo_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_cargo_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_cargo_id_seq TO usrsiaudi;


--
-- TOC entry 2923 (class 0 OID 0)
-- Dependencies: 192
-- Name: tb_categoria; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_categoria FROM PUBLIC;
REVOKE ALL ON TABLE tb_categoria FROM usrsiaudi;
GRANT ALL ON TABLE tb_categoria TO usrsiaudi;


--
-- TOC entry 2925 (class 0 OID 0)
-- Dependencies: 193
-- Name: tb_categoria_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_categoria_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_categoria_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_categoria_id_seq TO usrsiaudi;


--
-- TOC entry 2927 (class 0 OID 0)
-- Dependencies: 194
-- Name: tb_criterio; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_criterio FROM PUBLIC;
REVOKE ALL ON TABLE tb_criterio FROM usrsiaudi;
GRANT ALL ON TABLE tb_criterio TO usrsiaudi;


--
-- TOC entry 2929 (class 0 OID 0)
-- Dependencies: 195
-- Name: tb_criterio_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_criterio_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_criterio_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_criterio_id_seq TO usrsiaudi;


--
-- TOC entry 2931 (class 0 OID 0)
-- Dependencies: 196
-- Name: tb_diretoria; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_diretoria FROM PUBLIC;
REVOKE ALL ON TABLE tb_diretoria FROM usrsiaudi;
GRANT ALL ON TABLE tb_diretoria TO usrsiaudi;


--
-- TOC entry 2933 (class 0 OID 0)
-- Dependencies: 197
-- Name: tb_especie_auditoria; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_especie_auditoria FROM PUBLIC;
REVOKE ALL ON TABLE tb_especie_auditoria FROM usrsiaudi;
GRANT ALL ON TABLE tb_especie_auditoria TO usrsiaudi;


--
-- TOC entry 2935 (class 0 OID 0)
-- Dependencies: 198
-- Name: tb_especie_auditoria_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_especie_auditoria_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_especie_auditoria_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_especie_auditoria_id_seq TO usrsiaudi;


--
-- TOC entry 2936 (class 0 OID 0)
-- Dependencies: 199
-- Name: tb_feriado; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_feriado FROM PUBLIC;
REVOKE ALL ON TABLE tb_feriado FROM usrsiaudi;
GRANT ALL ON TABLE tb_feriado TO usrsiaudi;


--
-- TOC entry 2938 (class 0 OID 0)
-- Dependencies: 200
-- Name: tb_feriado2_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_feriado2_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_feriado2_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_feriado2_id_seq TO usrsiaudi;


--
-- TOC entry 2939 (class 0 OID 0)
-- Dependencies: 201
-- Name: tb_funcao; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_funcao FROM PUBLIC;
REVOKE ALL ON TABLE tb_funcao FROM usrsiaudi;
GRANT ALL ON TABLE tb_funcao TO usrsiaudi;


--
-- TOC entry 2941 (class 0 OID 0)
-- Dependencies: 202
-- Name: tb_funcao_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_funcao_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_funcao_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_funcao_id_seq TO usrsiaudi;


--
-- TOC entry 2943 (class 0 OID 0)
-- Dependencies: 203
-- Name: tb_homens_hora; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_homens_hora FROM PUBLIC;
REVOKE ALL ON TABLE tb_homens_hora FROM usrsiaudi;
GRANT ALL ON TABLE tb_homens_hora TO usrsiaudi;


--
-- TOC entry 2944 (class 0 OID 0)
-- Dependencies: 204
-- Name: tb_homens_hora_conf_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_homens_hora_conf_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_homens_hora_conf_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_homens_hora_conf_id_seq TO usrsiaudi;


--
-- TOC entry 2946 (class 0 OID 0)
-- Dependencies: 205
-- Name: tb_homens_hora_conf; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_homens_hora_conf FROM PUBLIC;
REVOKE ALL ON TABLE tb_homens_hora_conf FROM usrsiaudi;
GRANT ALL ON TABLE tb_homens_hora_conf TO usrsiaudi;


--
-- TOC entry 2948 (class 0 OID 0)
-- Dependencies: 206
-- Name: tb_homens_hora_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_homens_hora_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_homens_hora_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_homens_hora_id_seq TO usrsiaudi;


--
-- TOC entry 2950 (class 0 OID 0)
-- Dependencies: 207
-- Name: tb_imagem; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_imagem FROM PUBLIC;
REVOKE ALL ON TABLE tb_imagem FROM usrsiaudi;
GRANT ALL ON TABLE tb_imagem TO usrsiaudi;


--
-- TOC entry 2952 (class 0 OID 0)
-- Dependencies: 208
-- Name: tb_imagem_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_imagem_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_imagem_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_imagem_id_seq TO usrsiaudi;


--
-- TOC entry 2953 (class 0 OID 0)
-- Dependencies: 209
-- Name: tb_item; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_item FROM PUBLIC;
REVOKE ALL ON TABLE tb_item FROM usrsiaudi;
GRANT ALL ON TABLE tb_item TO usrsiaudi;


--
-- TOC entry 2955 (class 0 OID 0)
-- Dependencies: 210
-- Name: tb_item_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_item_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_item_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_item_id_seq TO usrsiaudi;


--
-- TOC entry 2957 (class 0 OID 0)
-- Dependencies: 211
-- Name: tb_log_entrada; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_log_entrada FROM PUBLIC;
REVOKE ALL ON TABLE tb_log_entrada FROM usrsiaudi;
GRANT ALL ON TABLE tb_log_entrada TO usrsiaudi;


--
-- TOC entry 2958 (class 0 OID 0)
-- Dependencies: 212
-- Name: tb_log_entrada_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_log_entrada_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_log_entrada_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_log_entrada_id_seq TO usrsiaudi;


--
-- TOC entry 2960 (class 0 OID 0)
-- Dependencies: 213
-- Name: tb_log_entrada_id_seq1; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_log_entrada_id_seq1 FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_log_entrada_id_seq1 FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_log_entrada_id_seq1 TO usrsiaudi;


--
-- TOC entry 2962 (class 0 OID 0)
-- Dependencies: 214
-- Name: tb_manifestacao; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_manifestacao FROM PUBLIC;
REVOKE ALL ON TABLE tb_manifestacao FROM usrsiaudi;
GRANT ALL ON TABLE tb_manifestacao TO usrsiaudi;


--
-- TOC entry 2964 (class 0 OID 0)
-- Dependencies: 215
-- Name: tb_manifestacao_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_manifestacao_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_manifestacao_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_manifestacao_id_seq TO usrsiaudi;


--
-- TOC entry 2976 (class 0 OID 0)
-- Dependencies: 293
-- Name: tb_menu; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_menu FROM PUBLIC;
REVOKE ALL ON TABLE tb_menu FROM usrsiaudi;
GRANT ALL ON TABLE tb_menu TO usrsiaudi;


--
-- TOC entry 2978 (class 0 OID 0)
-- Dependencies: 295
-- Name: tb_menu_perfil; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_menu_perfil FROM PUBLIC;
REVOKE ALL ON TABLE tb_menu_perfil FROM usrsiaudi;
GRANT ALL ON TABLE tb_menu_perfil TO usrsiaudi;


--
-- TOC entry 2983 (class 0 OID 0)
-- Dependencies: 297
-- Name: tb_modulo; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_modulo FROM PUBLIC;
REVOKE ALL ON TABLE tb_modulo FROM usrsiaudi;
GRANT ALL ON TABLE tb_modulo TO usrsiaudi;


--
-- TOC entry 2984 (class 0 OID 0)
-- Dependencies: 216
-- Name: tb_nucleo_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_nucleo_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_nucleo_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_nucleo_id_seq TO usrsiaudi;


--
-- TOC entry 2985 (class 0 OID 0)
-- Dependencies: 217
-- Name: tb_nucleo; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_nucleo FROM PUBLIC;
REVOKE ALL ON TABLE tb_nucleo FROM usrsiaudi;
GRANT ALL ON TABLE tb_nucleo TO usrsiaudi;


--
-- TOC entry 2986 (class 0 OID 0)
-- Dependencies: 218
-- Name: tb_objeto_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_objeto_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_objeto_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_objeto_id_seq TO usrsiaudi;


--
-- TOC entry 2988 (class 0 OID 0)
-- Dependencies: 219
-- Name: tb_objeto; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_objeto FROM PUBLIC;
REVOKE ALL ON TABLE tb_objeto FROM usrsiaudi;
GRANT ALL ON TABLE tb_objeto TO usrsiaudi;


--
-- TOC entry 2990 (class 0 OID 0)
-- Dependencies: 220
-- Name: tb_objeto_id_seq1; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_objeto_id_seq1 FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_objeto_id_seq1 FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_objeto_id_seq1 TO usrsiaudi;


--
-- TOC entry 2991 (class 0 OID 0)
-- Dependencies: 221
-- Name: tb_paint; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_paint FROM PUBLIC;
REVOKE ALL ON TABLE tb_paint FROM usrsiaudi;
GRANT ALL ON TABLE tb_paint TO usrsiaudi;


--
-- TOC entry 2993 (class 0 OID 0)
-- Dependencies: 222
-- Name: tb_paint_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_paint_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_paint_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_paint_id_seq TO usrsiaudi;


--
-- TOC entry 2994 (class 0 OID 0)
-- Dependencies: 223
-- Name: tb_perfil_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_perfil_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_perfil_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_perfil_id_seq TO usrsiaudi;


--
-- TOC entry 2995 (class 0 OID 0)
-- Dependencies: 224
-- Name: tb_perfil; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_perfil FROM PUBLIC;
REVOKE ALL ON TABLE tb_perfil FROM usrsiaudi;
GRANT ALL ON TABLE tb_perfil TO usrsiaudi;


--
-- TOC entry 2997 (class 0 OID 0)
-- Dependencies: 225
-- Name: tb_plan_especifico; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_plan_especifico FROM PUBLIC;
REVOKE ALL ON TABLE tb_plan_especifico FROM usrsiaudi;
GRANT ALL ON TABLE tb_plan_especifico TO usrsiaudi;


--
-- TOC entry 2999 (class 0 OID 0)
-- Dependencies: 226
-- Name: tb_plan_especifico_auditor; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_plan_especifico_auditor FROM PUBLIC;
REVOKE ALL ON TABLE tb_plan_especifico_auditor FROM usrsiaudi;
GRANT ALL ON TABLE tb_plan_especifico_auditor TO usrsiaudi;


--
-- TOC entry 3001 (class 0 OID 0)
-- Dependencies: 227
-- Name: tb_plan_especifico_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_plan_especifico_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_plan_especifico_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_plan_especifico_id_seq TO usrsiaudi;


--
-- TOC entry 3002 (class 0 OID 0)
-- Dependencies: 228
-- Name: tb_processo_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_processo_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_processo_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_processo_id_seq TO usrsiaudi;


--
-- TOC entry 3003 (class 0 OID 0)
-- Dependencies: 229
-- Name: tb_processo; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_processo FROM PUBLIC;
REVOKE ALL ON TABLE tb_processo FROM usrsiaudi;
GRANT ALL ON TABLE tb_processo TO usrsiaudi;


--
-- TOC entry 3004 (class 0 OID 0)
-- Dependencies: 230
-- Name: tb_processo_especie_auditoria; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_processo_especie_auditoria FROM PUBLIC;
REVOKE ALL ON TABLE tb_processo_especie_auditoria FROM usrsiaudi;
GRANT ALL ON TABLE tb_processo_especie_auditoria TO usrsiaudi;


--
-- TOC entry 3006 (class 0 OID 0)
-- Dependencies: 231
-- Name: tb_processo_risco_pre; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_processo_risco_pre FROM PUBLIC;
REVOKE ALL ON TABLE tb_processo_risco_pre FROM usrsiaudi;
GRANT ALL ON TABLE tb_processo_risco_pre TO usrsiaudi;


--
-- TOC entry 3007 (class 0 OID 0)
-- Dependencies: 232
-- Name: tb_raint_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_raint_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_raint_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_raint_id_seq TO usrsiaudi;


--
-- TOC entry 3008 (class 0 OID 0)
-- Dependencies: 233
-- Name: tb_raint; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_raint FROM PUBLIC;
REVOKE ALL ON TABLE tb_raint FROM usrsiaudi;
GRANT ALL ON TABLE tb_raint TO usrsiaudi;


--
-- TOC entry 3010 (class 0 OID 0)
-- Dependencies: 234
-- Name: tb_raint_id_seq1; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_raint_id_seq1 FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_raint_id_seq1 FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_raint_id_seq1 TO usrsiaudi;


--
-- TOC entry 3012 (class 0 OID 0)
-- Dependencies: 235
-- Name: tb_recomendacao; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_recomendacao FROM PUBLIC;
REVOKE ALL ON TABLE tb_recomendacao FROM usrsiaudi;
GRANT ALL ON TABLE tb_recomendacao TO usrsiaudi;


--
-- TOC entry 3013 (class 0 OID 0)
-- Dependencies: 236
-- Name: tb_recomendacao_categoria_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_recomendacao_categoria_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_recomendacao_categoria_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_recomendacao_categoria_id_seq TO usrsiaudi;


--
-- TOC entry 3015 (class 0 OID 0)
-- Dependencies: 237
-- Name: tb_recomendacao_categoria; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_recomendacao_categoria FROM PUBLIC;
REVOKE ALL ON TABLE tb_recomendacao_categoria FROM usrsiaudi;
GRANT ALL ON TABLE tb_recomendacao_categoria TO usrsiaudi;


--
-- TOC entry 3016 (class 0 OID 0)
-- Dependencies: 238
-- Name: tb_recomendacao_gravidade_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_recomendacao_gravidade_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_recomendacao_gravidade_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_recomendacao_gravidade_id_seq TO usrsiaudi;


--
-- TOC entry 3018 (class 0 OID 0)
-- Dependencies: 239
-- Name: tb_recomendacao_gravidade; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_recomendacao_gravidade FROM PUBLIC;
REVOKE ALL ON TABLE tb_recomendacao_gravidade FROM usrsiaudi;
GRANT ALL ON TABLE tb_recomendacao_gravidade TO usrsiaudi;


--
-- TOC entry 3019 (class 0 OID 0)
-- Dependencies: 240
-- Name: tb_recomendacao_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_recomendacao_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_recomendacao_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_recomendacao_id_seq TO usrsiaudi;


--
-- TOC entry 3021 (class 0 OID 0)
-- Dependencies: 241
-- Name: tb_recomendacao_id_seq1; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_recomendacao_id_seq1 FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_recomendacao_id_seq1 FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_recomendacao_id_seq1 TO usrsiaudi;


--
-- TOC entry 3022 (class 0 OID 0)
-- Dependencies: 242
-- Name: tb_recomendacao_padrao_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_recomendacao_padrao_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_recomendacao_padrao_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_recomendacao_padrao_id_seq TO usrsiaudi;


--
-- TOC entry 3024 (class 0 OID 0)
-- Dependencies: 243
-- Name: tb_recomendacao_padrao; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_recomendacao_padrao FROM PUBLIC;
REVOKE ALL ON TABLE tb_recomendacao_padrao FROM usrsiaudi;
GRANT ALL ON TABLE tb_recomendacao_padrao TO usrsiaudi;


--
-- TOC entry 3026 (class 0 OID 0)
-- Dependencies: 244
-- Name: tb_recomendacao_padrao_id_seq1; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_recomendacao_padrao_id_seq1 FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_recomendacao_padrao_id_seq1 FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_recomendacao_padrao_id_seq1 TO usrsiaudi;


--
-- TOC entry 3027 (class 0 OID 0)
-- Dependencies: 245
-- Name: tb_recomendacao_subcategoria_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_recomendacao_subcategoria_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_recomendacao_subcategoria_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_recomendacao_subcategoria_id_seq TO usrsiaudi;


--
-- TOC entry 3029 (class 0 OID 0)
-- Dependencies: 246
-- Name: tb_recomendacao_subcategoria; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_recomendacao_subcategoria FROM PUBLIC;
REVOKE ALL ON TABLE tb_recomendacao_subcategoria FROM usrsiaudi;
GRANT ALL ON TABLE tb_recomendacao_subcategoria TO usrsiaudi;


--
-- TOC entry 3030 (class 0 OID 0)
-- Dependencies: 247
-- Name: tb_recomendacao_tipo_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_recomendacao_tipo_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_recomendacao_tipo_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_recomendacao_tipo_id_seq TO usrsiaudi;


--
-- TOC entry 3032 (class 0 OID 0)
-- Dependencies: 248
-- Name: tb_recomendacao_tipo; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_recomendacao_tipo FROM PUBLIC;
REVOKE ALL ON TABLE tb_recomendacao_tipo FROM usrsiaudi;
GRANT ALL ON TABLE tb_recomendacao_tipo TO usrsiaudi;


--
-- TOC entry 3034 (class 0 OID 0)
-- Dependencies: 249
-- Name: tb_relatorio; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_relatorio FROM PUBLIC;
REVOKE ALL ON TABLE tb_relatorio FROM usrsiaudi;
GRANT ALL ON TABLE tb_relatorio TO usrsiaudi;


--
-- TOC entry 3036 (class 0 OID 0)
-- Dependencies: 250
-- Name: tb_relatorio_acesso; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_relatorio_acesso FROM PUBLIC;
REVOKE ALL ON TABLE tb_relatorio_acesso FROM usrsiaudi;
GRANT ALL ON TABLE tb_relatorio_acesso TO usrsiaudi;


--
-- TOC entry 3037 (class 0 OID 0)
-- Dependencies: 251
-- Name: tb_relatorio_acesso_item_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_relatorio_acesso_item_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_relatorio_acesso_item_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_relatorio_acesso_item_id_seq TO usrsiaudi;


--
-- TOC entry 3039 (class 0 OID 0)
-- Dependencies: 252
-- Name: tb_relatorio_acesso_item; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_relatorio_acesso_item FROM PUBLIC;
REVOKE ALL ON TABLE tb_relatorio_acesso_item FROM usrsiaudi;
GRANT ALL ON TABLE tb_relatorio_acesso_item TO usrsiaudi;


--
-- TOC entry 3041 (class 0 OID 0)
-- Dependencies: 253
-- Name: tb_relatorio_area; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_relatorio_area FROM PUBLIC;
REVOKE ALL ON TABLE tb_relatorio_area FROM usrsiaudi;
GRANT ALL ON TABLE tb_relatorio_area TO usrsiaudi;


--
-- TOC entry 3042 (class 0 OID 0)
-- Dependencies: 254
-- Name: tb_relatorio_auditor; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_relatorio_auditor FROM PUBLIC;
REVOKE ALL ON TABLE tb_relatorio_auditor FROM usrsiaudi;
GRANT ALL ON TABLE tb_relatorio_auditor TO usrsiaudi;


--
-- TOC entry 3043 (class 0 OID 0)
-- Dependencies: 255
-- Name: tb_relatorio_cabecalho_rodape_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_relatorio_cabecalho_rodape_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_relatorio_cabecalho_rodape_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_relatorio_cabecalho_rodape_id_seq TO usrsiaudi;


--
-- TOC entry 3044 (class 0 OID 0)
-- Dependencies: 256
-- Name: tb_relatorio_despacho; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_relatorio_despacho FROM PUBLIC;
REVOKE ALL ON TABLE tb_relatorio_despacho FROM usrsiaudi;
GRANT ALL ON TABLE tb_relatorio_despacho TO usrsiaudi;


--
-- TOC entry 3046 (class 0 OID 0)
-- Dependencies: 257
-- Name: tb_relatorio_diretoria; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_relatorio_diretoria FROM PUBLIC;
REVOKE ALL ON TABLE tb_relatorio_diretoria FROM usrsiaudi;
GRANT ALL ON TABLE tb_relatorio_diretoria TO usrsiaudi;


--
-- TOC entry 3047 (class 0 OID 0)
-- Dependencies: 258
-- Name: tb_relatorio_gerente; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_relatorio_gerente FROM PUBLIC;
REVOKE ALL ON TABLE tb_relatorio_gerente FROM usrsiaudi;
GRANT ALL ON TABLE tb_relatorio_gerente TO usrsiaudi;


--
-- TOC entry 3049 (class 0 OID 0)
-- Dependencies: 259
-- Name: tb_relatorio_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_relatorio_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_relatorio_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_relatorio_id_seq TO usrsiaudi;


--
-- TOC entry 3051 (class 0 OID 0)
-- Dependencies: 260
-- Name: tb_relatorio_reiniciar; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_relatorio_reiniciar FROM PUBLIC;
REVOKE ALL ON TABLE tb_relatorio_reiniciar FROM usrsiaudi;
GRANT ALL ON TABLE tb_relatorio_reiniciar TO usrsiaudi;


--
-- TOC entry 3053 (class 0 OID 0)
-- Dependencies: 261
-- Name: tb_relatorio_risco_pos; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_relatorio_risco_pos FROM PUBLIC;
REVOKE ALL ON TABLE tb_relatorio_risco_pos FROM usrsiaudi;
GRANT ALL ON TABLE tb_relatorio_risco_pos TO usrsiaudi;


--
-- TOC entry 3055 (class 0 OID 0)
-- Dependencies: 262
-- Name: tb_relatorio_setor; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_relatorio_setor FROM PUBLIC;
REVOKE ALL ON TABLE tb_relatorio_setor FROM usrsiaudi;
GRANT ALL ON TABLE tb_relatorio_setor TO usrsiaudi;


--
-- TOC entry 3057 (class 0 OID 0)
-- Dependencies: 263
-- Name: tb_relatorio_sureg; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_relatorio_sureg FROM PUBLIC;
REVOKE ALL ON TABLE tb_relatorio_sureg FROM usrsiaudi;
GRANT ALL ON TABLE tb_relatorio_sureg TO usrsiaudi;


--
-- TOC entry 3059 (class 0 OID 0)
-- Dependencies: 264
-- Name: tb_resposta; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_resposta FROM PUBLIC;
REVOKE ALL ON TABLE tb_resposta FROM usrsiaudi;
GRANT ALL ON TABLE tb_resposta TO usrsiaudi;


--
-- TOC entry 3061 (class 0 OID 0)
-- Dependencies: 265
-- Name: tb_resposta_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_resposta_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_resposta_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_resposta_id_seq TO usrsiaudi;


--
-- TOC entry 3068 (class 0 OID 0)
-- Dependencies: 299
-- Name: tb_restricao; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_restricao FROM PUBLIC;
REVOKE ALL ON TABLE tb_restricao FROM usrsiaudi;
GRANT ALL ON TABLE tb_restricao TO usrsiaudi;


--
-- TOC entry 3069 (class 0 OID 0)
-- Dependencies: 300
-- Name: tb_restricao_modulo_perfil; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_restricao_modulo_perfil FROM PUBLIC;
REVOKE ALL ON TABLE tb_restricao_modulo_perfil FROM usrsiaudi;
GRANT ALL ON TABLE tb_restricao_modulo_perfil TO usrsiaudi;


--
-- TOC entry 3071 (class 0 OID 0)
-- Dependencies: 266
-- Name: tb_risco_pos; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_risco_pos FROM PUBLIC;
REVOKE ALL ON TABLE tb_risco_pos FROM usrsiaudi;
GRANT ALL ON TABLE tb_risco_pos TO usrsiaudi;


--
-- TOC entry 3073 (class 0 OID 0)
-- Dependencies: 267
-- Name: tb_risco_pos_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_risco_pos_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_risco_pos_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_risco_pos_id_seq TO usrsiaudi;


--
-- TOC entry 3075 (class 0 OID 0)
-- Dependencies: 268
-- Name: tb_risco_pre; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_risco_pre FROM PUBLIC;
REVOKE ALL ON TABLE tb_risco_pre FROM usrsiaudi;
GRANT ALL ON TABLE tb_risco_pre TO usrsiaudi;


--
-- TOC entry 3077 (class 0 OID 0)
-- Dependencies: 269
-- Name: tb_risco_pre_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_risco_pre_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_risco_pre_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_risco_pre_id_seq TO usrsiaudi;


--
-- TOC entry 3078 (class 0 OID 0)
-- Dependencies: 301
-- Name: tb_sistema; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_sistema FROM PUBLIC;
REVOKE ALL ON TABLE tb_sistema FROM usrsiaudi;
GRANT ALL ON TABLE tb_sistema TO usrsiaudi;


--
-- TOC entry 3079 (class 0 OID 0)
-- Dependencies: 270
-- Name: tb_subcriterio; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_subcriterio FROM PUBLIC;
REVOKE ALL ON TABLE tb_subcriterio FROM usrsiaudi;
GRANT ALL ON TABLE tb_subcriterio TO usrsiaudi;


--
-- TOC entry 3081 (class 0 OID 0)
-- Dependencies: 271
-- Name: tb_subcriterio_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_subcriterio_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_subcriterio_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_subcriterio_id_seq TO usrsiaudi;


--
-- TOC entry 3082 (class 0 OID 0)
-- Dependencies: 272
-- Name: tb_subrisco; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_subrisco FROM PUBLIC;
REVOKE ALL ON TABLE tb_subrisco FROM usrsiaudi;
GRANT ALL ON TABLE tb_subrisco TO usrsiaudi;


--
-- TOC entry 3084 (class 0 OID 0)
-- Dependencies: 273
-- Name: tb_subrisco_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_subrisco_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_subrisco_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_subrisco_id_seq TO usrsiaudi;


--
-- TOC entry 3086 (class 0 OID 0)
-- Dependencies: 274
-- Name: tb_substituto_regional; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_substituto_regional FROM PUBLIC;
REVOKE ALL ON TABLE tb_substituto_regional FROM usrsiaudi;
GRANT ALL ON TABLE tb_substituto_regional TO usrsiaudi;


--
-- TOC entry 3088 (class 0 OID 0)
-- Dependencies: 275
-- Name: tb_substituto_regional_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_substituto_regional_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_substituto_regional_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_substituto_regional_id_seq TO usrsiaudi;


--
-- TOC entry 3089 (class 0 OID 0)
-- Dependencies: 276
-- Name: tb_sureg; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_sureg FROM PUBLIC;
REVOKE ALL ON TABLE tb_sureg FROM usrsiaudi;
GRANT ALL ON TABLE tb_sureg TO usrsiaudi;


--
-- TOC entry 3091 (class 0 OID 0)
-- Dependencies: 277
-- Name: tb_tipo_cliente; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_tipo_cliente FROM PUBLIC;
REVOKE ALL ON TABLE tb_tipo_cliente FROM usrsiaudi;
GRANT ALL ON TABLE tb_tipo_cliente TO usrsiaudi;


--
-- TOC entry 3093 (class 0 OID 0)
-- Dependencies: 278
-- Name: tb_tipo_cliente_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_tipo_cliente_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_tipo_cliente_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_tipo_cliente_id_seq TO usrsiaudi;


--
-- TOC entry 3095 (class 0 OID 0)
-- Dependencies: 279
-- Name: tb_tipo_criterio; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_tipo_criterio FROM PUBLIC;
REVOKE ALL ON TABLE tb_tipo_criterio FROM usrsiaudi;
GRANT ALL ON TABLE tb_tipo_criterio TO usrsiaudi;


--
-- TOC entry 3097 (class 0 OID 0)
-- Dependencies: 280
-- Name: tb_tipo_criterio_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_tipo_criterio_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_tipo_criterio_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_tipo_criterio_id_seq TO usrsiaudi;


--
-- TOC entry 3099 (class 0 OID 0)
-- Dependencies: 281
-- Name: tb_tipo_diretoria_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_tipo_diretoria_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_tipo_diretoria_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_tipo_diretoria_id_seq TO usrsiaudi;


--
-- TOC entry 3101 (class 0 OID 0)
-- Dependencies: 282
-- Name: tb_tipo_processo; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_tipo_processo FROM PUBLIC;
REVOKE ALL ON TABLE tb_tipo_processo FROM usrsiaudi;
GRANT ALL ON TABLE tb_tipo_processo TO usrsiaudi;


--
-- TOC entry 3103 (class 0 OID 0)
-- Dependencies: 283
-- Name: tb_tipo_processo_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_tipo_processo_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_tipo_processo_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_tipo_processo_id_seq TO usrsiaudi;


--
-- TOC entry 3105 (class 0 OID 0)
-- Dependencies: 284
-- Name: tb_tipo_status; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_tipo_status FROM PUBLIC;
REVOKE ALL ON TABLE tb_tipo_status FROM usrsiaudi;
GRANT ALL ON TABLE tb_tipo_status TO usrsiaudi;


--
-- TOC entry 3107 (class 0 OID 0)
-- Dependencies: 285
-- Name: tb_tipo_status_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_tipo_status_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_tipo_status_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_tipo_status_id_seq TO usrsiaudi;


--
-- TOC entry 3108 (class 0 OID 0)
-- Dependencies: 294
-- Name: tb_uf; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_uf FROM PUBLIC;
REVOKE ALL ON TABLE tb_uf FROM usrsiaudi;
GRANT ALL ON TABLE tb_uf TO usrsiaudi;


--
-- TOC entry 3109 (class 0 OID 0)
-- Dependencies: 286
-- Name: tb_unidade_administrativa; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_unidade_administrativa FROM PUBLIC;
REVOKE ALL ON TABLE tb_unidade_administrativa FROM usrsiaudi;
GRANT ALL ON TABLE tb_unidade_administrativa TO usrsiaudi;


--
-- TOC entry 3111 (class 0 OID 0)
-- Dependencies: 287
-- Name: tb_unidade_administrativa_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_unidade_administrativa_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_unidade_administrativa_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_unidade_administrativa_id_seq TO usrsiaudi;


--
-- TOC entry 3112 (class 0 OID 0)
-- Dependencies: 288
-- Name: tb_usuario_id_seq; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON SEQUENCE tb_usuario_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tb_usuario_id_seq FROM usrsiaudi;
GRANT ALL ON SEQUENCE tb_usuario_id_seq TO usrsiaudi;


--
-- TOC entry 3114 (class 0 OID 0)
-- Dependencies: 289
-- Name: tb_usuario; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE tb_usuario FROM PUBLIC;
REVOKE ALL ON TABLE tb_usuario FROM usrsiaudi;
GRANT ALL ON TABLE tb_usuario TO usrsiaudi;


--
-- TOC entry 3115 (class 0 OID 0)
-- Dependencies: 302
-- Name: vw_menu; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE vw_menu FROM PUBLIC;
REVOKE ALL ON TABLE vw_menu FROM usrsiaudi;
GRANT ALL ON TABLE vw_menu TO usrsiaudi;


--
-- TOC entry 3116 (class 0 OID 0)
-- Dependencies: 303
-- Name: vw_perfil; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE vw_perfil FROM PUBLIC;
REVOKE ALL ON TABLE vw_perfil FROM usrsiaudi;
GRANT ALL ON TABLE vw_perfil TO usrsiaudi;


--
-- TOC entry 3117 (class 0 OID 0)
-- Dependencies: 304
-- Name: vw_restricao; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE vw_restricao FROM PUBLIC;
REVOKE ALL ON TABLE vw_restricao FROM usrsiaudi;
GRANT ALL ON TABLE vw_restricao TO usrsiaudi;


--
-- TOC entry 3118 (class 0 OID 0)
-- Dependencies: 305
-- Name: vw_sistema; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE vw_sistema FROM PUBLIC;
REVOKE ALL ON TABLE vw_sistema FROM usrsiaudi;
GRANT ALL ON TABLE vw_sistema TO usrsiaudi;


--
-- TOC entry 3119 (class 0 OID 0)
-- Dependencies: 290
-- Name: vw_sureg; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE vw_sureg FROM PUBLIC;
REVOKE ALL ON TABLE vw_sureg FROM usrsiaudi;
GRANT ALL ON TABLE vw_sureg TO usrsiaudi;


--
-- TOC entry 3120 (class 0 OID 0)
-- Dependencies: 306
-- Name: vw_usuario; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE vw_usuario FROM PUBLIC;
REVOKE ALL ON TABLE vw_usuario FROM usrsiaudi;
GRANT ALL ON TABLE vw_usuario TO usrsiaudi;


--
-- TOC entry 3121 (class 0 OID 0)
-- Dependencies: 307
-- Name: vw_usuario_perfil; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE vw_usuario_perfil FROM PUBLIC;
REVOKE ALL ON TABLE vw_usuario_perfil FROM usrsiaudi;
GRANT ALL ON TABLE vw_usuario_perfil TO usrsiaudi;


--
-- TOC entry 3122 (class 0 OID 0)
-- Dependencies: 308
-- Name: vw_usuario_perfil_sistema; Type: ACL; Schema: siaudi; Owner: usrsiaudi
--

REVOKE ALL ON TABLE vw_usuario_perfil_sistema FROM PUBLIC;
REVOKE ALL ON TABLE vw_usuario_perfil_sistema FROM usrsiaudi;
GRANT ALL ON TABLE vw_usuario_perfil_sistema TO usrsiaudi;


-- Completed on 2015-06-03 09:54:35 BRT

--
-- PostgreSQL database dump complete
--

INSERT INTO tb_unidade_administrativa(
            id, nome, sigla, uf_fk, subordinante_fk, sureg, diretoria, data_extincao)
    VALUES (1, 'Unidade Administrativa 1', 'UA1', 'DF', null, null, null, null);

UPDATE tb_usuario SET unidade_administrativa_fk = 1 WHERE nome_login = 'siaudi.gerente';

-- Despacho padrao Siaudi
INSERT INTO siaudi.tb_relatorio_despacho VALUES (1, '<p>Por ter-se verificado a clareza, objetividade e imparcialidade no texto do relat&oacute;rio e o atendimento a requisitos t&eacute;cnicos t&iacute;picos de um trabalho de auditoria interna; a coer&ecirc;ncia entre os procedimentos adotados pela equipe de auditoria e o respectivo plano de trabalho, na forma de programa de auditoria; que os objetos de an&aacute;lise s&atilde;o condizentes com o escopo previamente definido para os trabalhos e que os pap&eacute;is de trabalho suportam adequadamente os achados e conclus&otilde;es oferecidas, manifesto-me de acordo.</p>', '<p>Ap&oacute;s supervis&atilde;o t&eacute;cnica por parte da Ger&ecirc;ncia de Auditoria, tendo-se verificado os atos de constitui&ccedil;&atilde;o e execu&ccedil;&atilde;o dos trabalhos aqui relatados, determino a convers&atilde;o da minuta em vers&atilde;o final de relat&oacute;rio de auditoria interna, o qual passa a ter car&aacute;ter institucional a partir deste ato homologat&oacute;rio.</p>', '<p>Por ter-se verificado que a presente a&ccedil;&atilde;o de auditoria foi executada&nbsp; em conson&acirc;ncia com o respectivo plano de trabalho e que as constata&ccedil;&otilde;es, objeto das an&aacute;lises empreendidas, apresentam os requisitos t&eacute;cnicos adequados, considero que esta minuta de relat&oacute;rio poder&aacute; ser submetida &agrave; Ger&ecirc;ncia de Auditoria, para finaliza&ccedil;&atilde;o.</p>');

-- Status follow-up padrao Siaudi
INSERT INTO siaudi.tb_tipo_status VALUES (1, 'Pendente'), (2, 'Em Implementação'), (3, 'Baixado'), (4, 'Solucionado');
