<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Hello, world!</title>


    <!-- Vue.js -->
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

  </head>
  <body>
    <h1>Resultado da pesquisa</h1>

    <div class="container-fluid">
        <div id="search">
            {{ request()->get('search') }}

            {{ \Request::get('search') }}
        </div>
    </div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>


    <script>
        var app = new Vue({
            el: '#search',
            data: {                
                teste:null
            },
            mounted: function () {
                this.getAllData()
            },
            methods: {
                getAllData() {
                    axios
                        .get("api/creative_work")
                        .then((response) => {
                            this.teste = response;
                        })
                        .catch(function (error) {
                            console.log(error);
                            this.errored = true;
                        });
                }
            }
        })
    </script>


  </body>
</html>
