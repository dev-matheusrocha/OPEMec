
-- produtos mais vendidos por Período
SELECT COUNT(dp.id_produto_venda) as qtdo, c.nome_produto, sum(c.valor_produto) as total_Rs 
FROM `tb_detalhe_pedido` as dp 
INNER JOIN tb_cardapio as c 
on c.id_produto_venda = dp.id_produto_venda 
INNER JOIN tb_pedido_finalizado as pf 
on pf.id_pedido_finalizado = dp.id_pedido_finalizado 
WHERE (pf.data >= '2019-08-01 00:00:00' AND pf.data <= '2019-08-02 00:00:00') 
GROUP BY dp.id_produto_venda 
ORDER BY `qtdo` DESC


-- manda informacoes para o whatsapp do motoboy
SELECT pf.id_pedido_finalizado , pf.valor_total as subtotal,  pf.frete, pf.data, c.* , pf.valor_total + pf.frete as total 
FROM `tb_pedido_finalizado` as pf 
INNER JOIN tb_cliente as c 
on c.id_cliente = pf.cliente 
WHERE frete > 0 AND cliente > 0 
AND motoboy IS NULL 
and pf.id_pedido_finalizado = 85




