<!DOCTYPE html>
<html>
<head>
    <title>Productos más vendidos</title>
</head>
<body>
    <h1>Reporte de Productos Más Vendidos</h1>
    <p><strong>Desde:</strong> {{ $startDate }}</p>
    <p><strong>Hasta:</strong> {{ $endDate }}</p>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>ID Producto</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Total Vendido</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr>
                <td>{{ $product['product_id'] }}</td>
                <td>{{ $product['name'] }}</td>
                <td>{{ $product['description'] }}</td>
                <td>{{ $product['total_sold'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
