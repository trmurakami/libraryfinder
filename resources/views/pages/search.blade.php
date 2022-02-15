@extends('layouts.default')
@section('title', 'LibraryFinder - Search results')

@section('vue')
    <!-- JS -->
    <script type="text/javascript" src="{{URL::asset('js/vue.js') }}"></script>
    <script type="text/javascript" src="{{URL::asset('js/axios.min.js') }}"></script>

@endsection

@section('content')

    <?php 

        if (!isset($_REQUEST['page'])) {
            $_REQUEST['page'] = '1';
        }

        if (!isset($_REQUEST['search'])) {
            $_REQUEST['search'] = '';
        }


    ?>

    <div class="container-fluid">
        <div id="search">
            {{ request()->get('search') }}

            {{ \Request::get('search') }}


            <!-- Pagination -->
            <nav aria-label="Page navigation">
              <ul class="pagination justify-content-center m-3">
                <li class="page-item">
                  <a class="page-link" @click="searchANDPaginate(response.data.first_page_url)">Primeira</a>
                </li>
                <template v-if="response.data.prev_page_url">
                  <li class="page-item" @click="searchANDPaginate(response.data.prev_page_url)">
                    <a class="page-link">Anterior</a>
                  </li>
                </template>
                <template v-else>
                  <li class="page-item disabled">
                    <a class="page-link">Anterior</a>
                  </li>
                </template>
                <template v-if="(response.data.current_page - 1) > 0">
                  <li class="page-item">
                    <a class="page-link disabled">...</a>
                  </li>
                </template>
                <template v-if="response.data.prev_page_url">
                  <li class="page-item">
                    <a class="page-link" @click="searchANDPaginate(response.data.prev_page_url)">@{{ response.data.current_page - 1 }}</a>
                  </li>
                </template>
                <li class="page-item active">
                  <a
                    class="page-link"                    
                    >@{{ response.data.current_page }}</a
                  >
                </li>
                <template v-if="response.data.next_page_url">
                  <li class="page-item">
                    <a class="page-link" @click="searchANDPaginate(response.data.next_page_url)">@{{ response.data.current_page + 1  }}</a>
                  </li>
                </template>
                <template v-if="response.data.next_page_url">
                  <li class="page-item">
                    <a class="page-link disabled">...</a>
                  </li>
                </template>
                <template v-if="response.data.next_page_url">
                  <li class="page-item">
                    <a class="page-link" @click="searchANDPaginate(response.data.next_page_url)">Próxima</a>
                  </li>
                </template>
                <template v-else>                
                  <li class="page-item disabled">
                    <a class="page-link">Próxima</a>
                  </li>
                </template>
                <li class="page-item">
                  <a class="page-link" @click="searchANDPaginate(response.data.last_page_url)">Última</a>
                </li>
              </ul>
            </nav>
            <!-- \ Pagination -->

            
            <div v-for="record in response.data.data" :key="record.id">
                <div class="card mb-3">
                    <div class="row g-0">
                        <div class="col-md-4">
                        <img src="" class="img-fluid rounded-start" alt="">
                        </div>
                        <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">@{{ record.name }}</h5>
                            <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                            <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            
            
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        var app = new Vue({
            el: '#search',
            data: {                
                response: null,
                request:{
                    page: <?php echo "'".$_REQUEST['page']."'" ?>,
                    search: <?php echo "'".$_REQUEST['search']."'" ?>
                }
            },
            mounted: function () {
                this.getAllData()
            },
            methods: {
                getAllData() {
                    axios
                        .get("api/creative_work?page=" + this.request.page)
                        .then((response) => {
                            this.response = response;
                        })
                        .catch(function (error) {
                            console.log(error);
                            this.errored = true;
                        });
                },
                searchANDPaginate(queryAPIURL) {
                    axios
                        .get(queryAPIURL)
                        .then((response) => {
                            this.response = response;
                        })
                        .catch(function (error) {
                            console.log(error);
                            this.errored = true;
                        });
                }
            }
        })
    </script>
@endsection