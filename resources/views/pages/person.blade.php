@extends('layouts.default')
@section('title', 'LibraryFinder - CreativeWork')

@section('vue')
    <!-- JS -->
    <script type="text/javascript" src="{{URL::asset('js/vue.js') }}"></script>
    <script type="text/javascript" src="{{URL::asset('js/axios.min.js') }}"></script>

@endsection

@section('content')

<h1>{{ $record->name }}</h1>

{{ $record }}

@endsection

@section('scripts')

<script>
        var app = new Vue({
            el: '#creativework',
            data: {                
                response: null,
                request:{
                    id: ''
                },
                errored: false
            },
            mounted: function () {

            },
            methods: {
                getRecordData() {
                    axios
                        .get("api/person/" + this.request.id)
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
