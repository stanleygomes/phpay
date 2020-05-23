@extends('layouts.website')
@section('pageTitle', 'Dúvidas frequentes')

@section('content')

<header class="bg-light">
    <div class="container text-center">
        <h1>Dúvidas frequentes</h1>
        <p class="lead">Lorem ipsum dolor sit amet consectetur adipisicing elit. Error, reprehenderit.</p>
    </div>
</header>

<section id="about">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                @foreach([1,2,3,4] as $value)
                <h2><strong>Duvida frequente</strong></h2>
                <p class="lead">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Quam laborum doloribus perspiciatis unde, ut temporibus nobis at nulla eius suscipit eum non itaque neque impedit debitis est rem incidunt et?
                </p>
                <p class="lead">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Quam laborum doloribus perspiciatis unde, ut temporibus nobis at nulla eius suscipit eum non itaque neque impedit debitis est rem incidunt et?
                </p>
                @endforeach
            </div>
        </div>
    </div>
</section>

@endsection
