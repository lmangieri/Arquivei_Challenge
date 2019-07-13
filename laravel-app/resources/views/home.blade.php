<html>
<header>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content1 {
                text-align: center;
                /* background-color:lightblue; */

            }

            .content2 {
                text-align: center;
                /* border: 1px solid black; */
                margin-bottom: 10px;
                margin-top: 40px;
                padding-bottom: 10px;
                padding-top: 10px;
               /* background-color:lightgray; */

            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .mg {
                margin-bottom: 5px;
                margin-top: 5px;
            }
        </style>
</header>

    <body>
        <div class="container">

            <div class="content1">
                <h1>Desafio - Arquivei</h1>
                <h2>Leandro Henrique Mangieri</h2>
            </div>

            <div class="content2">

                <a href="{{ url('/invoices/loadFromArquivei' ) }}" type="button" class="btn btn-success">Carregar Notas Fiscais</a>

            </div>

            <div class="content2">
                <form>
                    <input type="text" class="form-control mg" id="access_key"  placeholder="access_key">

                    <button type="button" class="btn btn-success mg">Buscar Nota Fiscal</button>
                </form>
            </div>

            <div class="content2">
                <button type="button" class="btn btn-success mg">Listar Access Keys</button>
            </div>

        </div>



        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </body>
</html>

