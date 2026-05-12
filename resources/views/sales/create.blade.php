@extends('layouts.app') 

@section('content')
    <h1>Crear Venta</h1>

    @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{route('sales.store')}}" method="POST">
        @csrf
        <div>
            <label>Buscar cliente:</label>
            <input type="text" id = "searchClient" class="form-control" placeholder = "Escribe el nombre o el número de documento..." autocomplete="off" style="width: 100%; padding: 8px;">

            <ul id = "resultsClients" style="display: none; position: absolute; width: 100%; background: white; border: 1px solid #ccc; list-style: none; padding: 0; margin: 0; z-index: 1000; max-height: 200px; overflow-y: auto;"></ul>

            <input type="hidden" id = "temp_client_id" name="client_id">
            
        </div>
        <div>
            <label>Voucher:</label>
            <select name="voucher" class="form-control mb-2">
                @foreach($vouchers as $voucher)
                    <option value="{{$voucher}}" {{old('voucher') == $voucher ? 'selected':''}}>{{$voucher}}</option>
                @endforeach
            </select>
        </div>
        
        <div style="position: relative; margin-bottom: 15px;">
            <label>Buscar producto a vender:</label>
            <input type="text" id="searchInput" class="form-control" placeholder="Escribe el modelo o número de serie..." autocomplete="off" style="width: 100%; padding: 8px;">
            
            <ul id="searchResults" style="display: none; position: absolute; width: 100%; background: white; border: 1px solid #ccc; list-style: none; padding: 0; margin: 0; z-index: 1000; max-height: 200px; overflow-y: auto;">
                </ul>

            <input type="hidden" id="temp_unit_id">
            <input type="hidden" id="temp_unit_name">
            <input type="hidden" id="temp_unit_serial">
            <input type="hidden" id="temp_unit_price">

            <button type="button" id="btn_add" style="margin-top: 10px;">Agregar Producto</button>
        </div>
        <div>
            <table style="width: 100%; text-align: left; border-collapse: collapse; margin-bottom: 20px;" border="1">
                <thead style="background-color: #f2f2f2;">
                    <tr>
                        <th>Producto</th>
                        <th>Numero de serie</th>
                        <th>Precio</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody id="details_body">
                </tbody>
            </table>

            <div>
                <h4>Subtotal: S/ <span id="txt_subtotal">0.00</span></h4>
                <h4>IGV (18%): S/ <span id="txt_igv">0.00</span></h4>
                <h2>Total: S/ <span id="txt_total">0.00</span></h2>
            </div>
            
        </div>

        <button type="submit">CONFIRMAR</button>
        <a href="{{route('sales.index')}}">CANCELAR</a>
    </form>

    <script>
        const searchClient = document.getElementById('searchClient');
        const resultsClients = document.getElementById('resultsClients')

        const tempClientId = document.getElementById('temp_client_id');

        searchClient.addEventListener('keyup', function() {
            let query = this.value;

            if(query.length === 0) {
                resultsClients.style.display = 'none';
                resultsClients.innerHTML = '';
                return
            }

            fetch(`{{route('api.clients.search')}}?q=${query}`)
                .then(response => response.json())
                .then(data => {                              // abre then
                    console.log("Datos recibidos de Laravel:", data); 
                    resultsClients.innerHTML = '';
                    
                    if(data.length === 0) {                  // abre if
                        resultsClients.innerHTML = '<li>No se encontraron clientes disponibles</li>';
                    } else {                                 // abre else
                        data.forEach(client => {             // abre forEach
                            let li = document.createElement('li');
                            li.style.cssText = 'padding: 10px; border-bottom: 1px solid #eee; cursor: pointer; color: black;';
                            li.innerHTML = `<strong>Nombre: ${client.name}</strong> - ${client.document_type}: ${client.document_number}`;
                            li.onmouseover = function() { this.style.backgroundColor = '#f8f9fa'; }
                            li.onmouseout = function() { this.style.backgroundColor = 'white'; }
                            li.addEventListener('click', function() {
                                tempClientId.value = client.id;
                                searchClient.value = `${client.name} (${client.document_type}: ${client.document_number})`;
                                resultsClients.style.display = 'none';
                            });
                            resultsClients.appendChild(li);
                        });                                  // cierra forEach
                    }                                        // cierra else
                    
                    resultsClients.style.display = 'block';
                    
                })                                           // cierra then
                .catch(error => {
                    console.error('Error en la petición AJAX:', error);
                });
        });

        // Ocultar resultados si hacen clic afuera
        document.addEventListener('click', function(event) {
            if (!searchClient.contains(event.target) && !resultsClients.contains(event.target)) {
                resultsClients.style.display = 'none';
            }
        });


        //LOGICA DE BUSCAR UNIDADES
        // --- 1. LÓGICA DEL BUSCADOR AJAX ---
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');
        const baseUrl = "{{ asset('') }}";
        
        const tempUnitId = document.getElementById('temp_unit_id');
        const tempUnitName = document.getElementById('temp_unit_name');
        const tempUnitSerial = document.getElementById('temp_unit_serial');
        const tempUnitPrice = document.getElementById('temp_unit_price');

        searchInput.addEventListener('keyup', function() {
            let query = this.value;

            if (query.length === 0) {
                searchResults.style.display = 'none';
                searchResults.innerHTML = '';
                return;
            }

            fetch(`{{ route('api.units.search') }}?q=${query}`)
                .then(response => response.json())
                .then(data => {
                    // 1. IMPRIMIMOS EN CONSOLA PARA VER QUE LLEGA
                    console.log("Datos recibidos de Laravel:", data); 

                    searchResults.innerHTML = ''; 

                    if (data.length === 0) {
                        searchResults.innerHTML = '<li style="padding: 10px; color: #666;">No se encontraron laptops disponibles.</li>';
                    } else {
                        data.forEach(unit => {
                            // 2. VALIDAMOS QUE EL PRODUCTO EXISTA PARA EVITAR ERRORES JS
                            let productName = unit.product ? unit.product.name : 'Producto sin nombre';
                            let productPrice = unit.product ? unit.product.sale_price : 0;

                            let li = document.createElement('li');
                            li.style.cssText = 'padding: 10px; border-bottom: 1px solid #eee; cursor: pointer; color: black;';
                    
                            let imagePath = unit.product.image 
                                ? `${baseUrl}/storage/${unit.product.image}` 
                                : `${baseUrl}/img/no-image.png`;
                            
                            console.log(imagePath);

                            li.innerHTML = `
                                <div style="display: flex; align-items: center;">
                                    <img src="${imagePath}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px; margin-right: 10px;">
                                    <div>
                                        <strong style="display: block;">${productName}</strong>
                                        <small style="color: #666;">Serie: ${unit.serial_number}</small>
                                    </div>
                                </div>
                            `;


                            
                            li.onmouseover = function() { this.style.backgroundColor = '#f8f9fa'; }
                            li.onmouseout = function() { this.style.backgroundColor = 'white'; }

                            li.addEventListener('click', function() {
                                tempUnitId.value = unit.id;
                                tempUnitName.value = productName;
                                tempUnitSerial.value = unit.serial_number;
                                tempUnitPrice.value = productPrice;

                                searchInput.value = `${productName} (Serie: ${unit.serial_number})`;
                                searchResults.style.display = 'none';
                            });

                            searchResults.appendChild(li);
                        });
                    }
                    searchResults.style.display = 'block';
                })
                .catch(error => {
                    // SI HAY UN ERROR, LO MOSTRAMOS EN ROJO EN LA CONSOLA
                    console.error('Error en la petición AJAX:', error);
                });
        });

        // Ocultar resultados si hacen clic afuera
        document.addEventListener('click', function(event) {
            if (!searchInput.contains(event.target) && !searchResults.contains(event.target)) {
                searchResults.style.display = 'none';
            }
        });


        // --- 2. LÓGICA DEL CARRITO (Tus funciones originales intactas) ---
        function removeRow(id) {
            const row = document.getElementById(`row_${id}`);
            if (row) {
                row.remove();
                calculateTotals();
            }
        }

        function calculateTotals() {
            let total = 0;
            const priceInputs = document.querySelectorAll('input[name="prices[]"]');

            priceInputs.forEach(function(input) {
                total += parseFloat(input.value);
            });

            const subtotal = total / 1.18;
            const igv = total - subtotal;

            document.getElementById('txt_subtotal').innerText = subtotal.toFixed(2);
            document.getElementById('txt_igv').innerText = igv.toFixed(2);
            document.getElementById('txt_total').innerText = total.toFixed(2);
        }

        // --- 3. LÓGICA DEL BOTÓN AGREGAR MODIFICADA ---
        const btnAdd = document.getElementById('btn_add');

        btnAdd.addEventListener('click', function() {
            // Extraemos los datos de los inputs ocultos en lugar del <select>
            const unitId = tempUnitId.value;
            const unitName = tempUnitName.value;
            const unitSerial = tempUnitSerial.value;
            const unitPrice = tempUnitPrice.value;

            if(unitId === "") {
                alert("Por favor, busque y seleccione una laptop de la lista primero.");
                return; 
            }

            const tbody = document.getElementById('details_body');
            const rowHTML = `
                <tr id="row_${unitId}">
                    <td> ${unitName} </td>
                    <td> ${unitSerial} </td>
                    <td>
                        <input type="hidden" name="prices[]" value="${unitPrice}">
                        <input type="hidden" name="unit_ids[]" value="${unitId}">
                        S/ ${parseFloat(unitPrice).toFixed(2)}
                    </td>
                    <td>
                        <button type="button" onClick="removeRow(${unitId})">Quitar</button>
                    </td>
                </tr>
            `;

            const row = document.getElementById(`row_${unitId}`);
            if(row) {
                alert("Ese producto ya está agregado");
                return;
            }

            tbody.insertAdjacentHTML('beforeend', rowHTML);
            calculateTotals();

            // LIMPIAR EL BUSCADOR PARA EL SIGUIENTE PRODUCTO
            searchInput.value = '';
            tempUnitId.value = '';
            tempUnitName.value = '';
            tempUnitSerial.value = '';
            tempUnitPrice.value = '';
        });
        
        // Prevenir doble envío del formulario
        const form = document.querySelector('form');
        const submitBtn = form.querySelector('button[type="submit"]');

        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Procesando...';
            submitBtn.style.opacity = '0.6';
        });

    </script>
@endsection