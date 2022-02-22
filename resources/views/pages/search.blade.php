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
                                    <h5 class="card-title"><a :href="'creativework/' + record.id ">@{{ record.name }} (@{{ record.datePublished }})</a></h5>
                                    <p class="card-text">@{{ record.type }}</p>
                                    <p class="card-text"><small class="text-muted">@{{ record.doi }}</small></p>
                                    <p class="card-text"><small class="text-muted">@{{ record.url }}</small></p>
                                    <!-- <p class="card-text">@{{ record.authors }}</p> -->
                                    <div v-for="author in record.authors" :key="author.id">
                                        <small class="text-muted">@{{ author.name }} <template v-if='author.lattesID'><a :href="'https://lattes.cnpq.br/' + author.lattesID">Lattes</a></template>
                                            <template v-if='author.orcidID'><a :href="author.orcidID">ORCID</a></template></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-4">

                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Por tipo
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center" v-for="type in facets.type" :key="type.names">
                                        <a :href="currentURL + '&type=' + type.type">@{{ type.type }}</a>
                                        <span class="badge bg-primary rounded-pill">@{{ type.total }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Idioma
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center" v-for="language in facets.inLanguage" :key="language.names">
                                        <a :href="currentURL + '&inLanguage=' + language.inLanguage">@{{ language.inLanguage }}</a>
                                        <span class="badge bg-primary rounded-pill">@{{ language.total }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                País do evento
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse show" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center" v-for="countryOfOrigin in facets.countryOfOrigin" :key="countryOfOrigin.countryOfOrigin">
                                        <a :href="currentURL + '&countryOfOrigin=' + countryOfOrigin.countryOfOrigin">@{{ countryOfOrigin.countryOfOrigin }}</a>
                                        <span class="badge bg-primary rounded-pill">@{{ countryOfOrigin.total }}</span>
                                    </li>
                                </ul>
                            </div>
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
            currentURL: null,
            errored: false,
            facets: {
                countryOfOrigin: null,
                inLanguage: null,
                type: null
            },
            response: null,
            request: {
                countryOfOrigin: <?php echo "'" . $_REQUEST['countryOfOrigin'] . "'" ?>,
                inLanguage: <?php echo "'" . $_REQUEST['inLanguage'] . "'" ?>,
                page: <?php echo "'" . $_REQUEST['page'] . "'" ?>,
                search: <?php echo "'" . $_REQUEST['search'] . "'" ?>,
                type: <?php echo "'" . $_REQUEST['type'] . "'" ?>
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
                    .get("api/creative_work?page=" + 
                            this.request.page + 
                            '&search=' + 
                            this.request.search + 
                            (this.request.type ? '&type=' + this.request.type : '') +
                            (this.request.countryOfOrigin ? '&countryOfOrigin=' + this.request.countryOfOrigin : '') +
                            (this.request.inLanguage ? '&inLanguage=' + this.request.inLanguage : '')
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