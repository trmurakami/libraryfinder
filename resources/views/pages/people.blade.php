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

if (!isset($_REQUEST['type'])) {
    $_REQUEST['type'] = '';
}

if (!isset($_REQUEST['countryOfOrigin'])) {
    $_REQUEST['countryOfOrigin'] = '';
}

if (!isset($_REQUEST['inLanguage'])) {
    $_REQUEST['inLanguage'] = '';
}


?>

<div class="container-fluid">
    <div id="search">

        <div class="row">
            <div class="col-8">

                <!-- Pagination -->
                <nav aria-label="Page navigation">
                    <p>Você pesquisou por: {{ \Request::get('search') }}</p>
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
                            <a class="page-link">@{{ response.data.current_page }}</a>
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
                                    <h5 class="card-title"><a :href="'person/' + record.id ">@{{ record.name }}</a></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-4">

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
            currentURL: null,
            errored: false,
            facets: {
                countryOfOrigin: null,
                inLanguage: null,
                type: null
            },
            response: null,
            request: {
                page: <?php echo "'" . $_REQUEST['page'] . "'" ?>,
                search: <?php echo "'" . $_REQUEST['search'] . "'" ?>
            }
        },
        mounted: function() {
            this.getAllData();
            this.facetSimple('countryOfOrigin');
            this.facetSimple('type');
            this.facetSimple('inLanguage');
            if (window.location.href.includes("\?")) {
                this.currentURL = window.location.href;
            } else {
                this.currentURL = window.location.href + '?';
            }
        },
        methods: {
            getAllData() {
                axios
                    .get("api/people?page=" + 
                            this.request.page + 
                            '&search=' + 
                            this.request.search
                        )
                    .then((response) => {
                        this.response = response;
                    })
                    .catch(function(error) {
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
                    .catch(function(error) {
                        console.log(error);
                        this.errored = true;
                    });
            },
            facetSimple(field) {
                axios
                    .get("api/facets/simple?field=" + 
                            field + 
                            '&search=' + 
                            this.request.search + 
                            (this.request.type ? '&type=' + this.request.type : '') +
                            (this.request.countryOfOrigin ? '&countryOfOrigin=' + this.request.countryOfOrigin : '') +
                            (this.request.inLanguage ? '&inLanguage=' + this.request.inLanguage : '')
                        )
                    .then((response) => {
                        this.facets[field] = response.data;
                    })
                    .catch(function(error) {
                        console.log(error);
                        this.errored = true;
                    });
            }
        }
    })
</script>
@endsection