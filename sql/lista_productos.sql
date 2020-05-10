DROP VIEW IF EXISTS lista_productos;
CREATE VIEW lista_productos as 
select p.grabo, u.nombre, p.cod_prodcuto, p.descripcion, p.precio, p.peso, p.marca from productos as p
INNER JOIN usuarios as u on u.cod_usuario = p.grabo;