SELECT car.vendedor,p.descripcion, p.precio, car.cantidad, (car.cantidad * p.precio) as precio_parcial FROM carrito as car
INNER JOIN usuarios as c on c.cod_usuario = car.cod_usuario
INNER JOIN usuarios as v on car.vendedor = v.cod_usuario
INNER JOIN productos as p on p.cod_producto = car.cod_producto AND car.vendedor = p.grabo