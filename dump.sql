--
-- PostgreSQL database dump
--

-- Dumped from database version 16.2 (Debian 16.2-1.pgdg120+2)
-- Dumped by pg_dump version 16.2 (Debian 16.2-1.pgdg120+2)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: itens_venda; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.itens_venda (
    id integer NOT NULL,
    quantidade integer,
    valor_unitario numeric(10,2),
    valor_total numeric(10,2),
    produto_id integer,
    venda_id integer,
    valor_imposto numeric(10,2)
);


ALTER TABLE public.itens_venda OWNER TO root;

--
-- Name: itens_venda_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.itens_venda_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.itens_venda_id_seq OWNER TO root;

--
-- Name: itens_venda_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.itens_venda_id_seq OWNED BY public.itens_venda.id;


--
-- Name: produtos; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.produtos (
    id integer NOT NULL,
    nome character varying(255),
    preco numeric(10,2),
    tipo_produto_id integer
);


ALTER TABLE public.produtos OWNER TO root;

--
-- Name: produtos_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.produtos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.produtos_id_seq OWNER TO root;

--
-- Name: produtos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.produtos_id_seq OWNED BY public.produtos.id;


--
-- Name: tipo_produtos; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.tipo_produtos (
    id integer NOT NULL,
    nome character varying(255),
    porcentagem_imposto numeric(10,2)
);


ALTER TABLE public.tipo_produtos OWNER TO root;

--
-- Name: tipo_produtos_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.tipo_produtos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tipo_produtos_id_seq OWNER TO root;

--
-- Name: tipo_produtos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.tipo_produtos_id_seq OWNED BY public.tipo_produtos.id;


--
-- Name: vendas; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.vendas (
    id integer NOT NULL,
    data timestamp without time zone,
    valor_total numeric(10,2),
    valor_total_imposto numeric(10,2)
);


ALTER TABLE public.vendas OWNER TO root;

--
-- Name: vendas_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.vendas_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.vendas_id_seq OWNER TO root;

--
-- Name: vendas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.vendas_id_seq OWNED BY public.vendas.id;


--
-- Name: itens_venda id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.itens_venda ALTER COLUMN id SET DEFAULT nextval('public.itens_venda_id_seq'::regclass);


--
-- Name: produtos id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.produtos ALTER COLUMN id SET DEFAULT nextval('public.produtos_id_seq'::regclass);


--
-- Name: tipo_produtos id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.tipo_produtos ALTER COLUMN id SET DEFAULT nextval('public.tipo_produtos_id_seq'::regclass);


--
-- Name: vendas id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.vendas ALTER COLUMN id SET DEFAULT nextval('public.vendas_id_seq'::regclass);


--
-- Data for Name: itens_venda; Type: TABLE DATA; Schema: public; Owner: root
--

COPY public.itens_venda (id, quantidade, valor_unitario, valor_total, produto_id, venda_id, valor_imposto) FROM stdin;
6       2       129.90  259.80  3       6       20.26
7       1       60.00   60.00   7       7       9.54
8       1       60.00   60.00   7       7       9.54
9       1       600.00  600.00  9       7       95.40
10      1       300.00  300.00  8       7       47.70
11      3       59.90   179.70  6       8       17.97
13      1       212.00  212.00  12      10      111.94
14      1       100.00  100.00  13      10      52.80
\.


--
-- Data for Name: produtos; Type: TABLE DATA; Schema: public; Owner: root
--

COPY public.produtos (id, nome, preco, tipo_produto_id) FROM stdin;
5       Toalha de banho 59.90   7
6       Camisa  59.90   3
7       Memória RAM     60.00   10
8       Placa mãe       300.00  10
9       Monitor 600.00  10
10      Camisa  51.00   3
11      Detergente      4.99    16
12      212     212.00  13
13      Malbec  100.00  13
3       Tênis de corrida        129.90  8
\.


--
-- Data for Name: tipo_produtos; Type: TABLE DATA; Schema: public; Owner: root
--

COPY public.tipo_produtos (id, nome, porcentagem_imposto) FROM stdin;
3       Nacional        10.00
4       Importado       50.00
5       Cama    5.00
6       Mesa    6.00
7       Banho   8.00
8       Esporte 7.80
9       Infantil        12.00
10      Eletrônicos     15.90
11      Celular 20.10
12      Informática     14.90
13      Perfumes        52.80
14      Cosméticos      17.70
15      Maquiagem       11.00
16      Limpeza 42.00
\.


--
-- Data for Name: vendas; Type: TABLE DATA; Schema: public; Owner: root
--

COPY public.vendas (id, data, valor_total, valor_total_imposto) FROM stdin;
6       2024-04-11 03:52:27     259.80  20.26
7       2024-04-11 04:47:03     1020.00 162.18
8       2024-04-11 04:47:26     179.70  17.97
10      2024-04-11 04:48:50     312.00  164.74
\.


--
-- Name: itens_venda_id_seq; Type: SEQUENCE SET; Schema: public; Owner: root
--

SELECT pg_catalog.setval('public.itens_venda_id_seq', 14, true);


--
-- Name: produtos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: root
--

SELECT pg_catalog.setval('public.produtos_id_seq', 13, true);


--
-- Name: tipo_produtos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: root
--

SELECT pg_catalog.setval('public.tipo_produtos_id_seq', 16, true);


--
-- Name: vendas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: root
--

SELECT pg_catalog.setval('public.vendas_id_seq', 10, true);


--
-- Name: itens_venda itens_venda_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.itens_venda
    ADD CONSTRAINT itens_venda_pkey PRIMARY KEY (id);


--
-- Name: produtos produtos_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.produtos
    ADD CONSTRAINT produtos_pkey PRIMARY KEY (id);


--
-- Name: tipo_produtos tipo_produtos_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.tipo_produtos
    ADD CONSTRAINT tipo_produtos_pkey PRIMARY KEY (id);


--
-- Name: vendas vendas_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.vendas
    ADD CONSTRAINT vendas_pkey PRIMARY KEY (id);


--
-- Name: itens_venda itens_venda_produto_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.itens_venda
    ADD CONSTRAINT itens_venda_produto_id_fkey FOREIGN KEY (produto_id) REFERENCES public.produtos(id);


--
-- Name: itens_venda itens_venda_venda_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.itens_venda
    ADD CONSTRAINT itens_venda_venda_id_fkey FOREIGN KEY (venda_id) REFERENCES public.vendas(id);


--
-- Name: produtos produtos_tipo_produto_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.produtos
    ADD CONSTRAINT produtos_tipo_produto_id_fkey FOREIGN KEY (tipo_produto_id) REFERENCES public.tipo_produtos(id);


--
-- PostgreSQL database dump complete
--