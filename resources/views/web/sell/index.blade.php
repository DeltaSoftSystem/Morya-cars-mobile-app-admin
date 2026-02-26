@extends('web.layouts.app')
@section('title','Sell Your Car')

@section('content')

<div class="container-xl my-5" x-data="sellForm()">

    <h1 class="mb-4">Sell your car</h1>

    <div class="sell-wrapper">

        <h4 class="mb-4">Fill all details about your car</h4>

        {{-- PROGRESS --}}
        <div class="progress-wrapper mb-5">
            <div class="progress-line">
                <div class="progress-active"
                     :style="'width:'+progressWidth+'%'">
                </div>
            </div>

            <template x-for="(label,index) in steps">
                <div class="step-item"
                     :class="{'active':step>=index+1}">
                    <div class="dot"></div>
                    <p x-text="label"></p>
                </div>
            </template>
        </div>

        {{-- STEP 1 --}}
        <div x-show="step===1" x-transition>
            <h5 class="mb-4">Contact Details</h5>

            <div class="row g-4">
                <div class="col-lg-6">
                    <input type="text"
                           class="sell-input"
                           placeholder="Full Name *"
                           x-model="form.name">
                    <small class="text-danger" x-text="errors.name"></small>
                </div>

                <div class="col-lg-6">
                    <input type="tel"
                           class="sell-input"
                           placeholder="Phone Number *"
                           x-model="form.phone">
                    <small class="text-danger" x-text="errors.phone"></small>
                </div>
            </div>
        </div>

        {{-- STEP 2 --}}
        <div x-show="step===2" x-transition>
            <h5 class="mb-4">Car Details</h5>

            <div class="row g-4">
                <div class="col-md-6">
                    <select x-model="form.make_id"
                            @change="fetchModels"
                            class="sell-input">
                        <option value="">Select Make</option>
                        @foreach($makes as $make)
                            <option value="{{ $make->id }}">{{ $make->name }}</option>
                        @endforeach
                    </select>
                    <small class="text-danger" x-text="errors.make_id"></small>
                </div>

                <div class="col-md-6">
                    <select x-model="form.model_id" class="sell-input">
                        <option value="">Select Model</option>
                        <template x-for="model in models" :key="model.id">
                            <option :value="model.id" x-text="model.name"></option>
                        </template>
                    </select>
                    <small class="text-danger" x-text="errors.model_id"></small>
                </div>

                <div class="col-md-6">
                    <input type="number"
                           class="sell-input"
                           placeholder="Year"
                           x-model="form.year">
                    <small class="text-danger" x-text="errors.year"></small>
                </div>

                <div class="col-md-6">
                    <input type="number"
                           class="sell-input"
                           placeholder="Expected Price"
                           x-model="form.price">
                    <small class="text-danger" x-text="errors.price"></small>
                </div>
            </div>
        </div>

        {{-- STEP 3 --}}
        <div x-show="step===3" x-transition>
            <h5 class="mb-4">Upload Images</h5>

            <input type="file"
                   multiple
                   class="sell-input"
                   @change="handleImages($event)">

            <div class="row mt-3">
                <template x-for="img in previewImages">
                    <div class="col-3 mb-3">
                        <img :src="img" class="img-fluid rounded">
                    </div>
                </template>
            </div>
        </div>

        {{-- SUCCESS --}}
        <div x-show="step===4" class="text-center py-5">
            <h3 class="text-success mb-3">
                Your car submitted successfully 🎉
            </h3>
            <p>Our team will contact you shortly.</p>
        </div>

        {{-- BUTTONS --}}
        <div class="d-flex justify-content-between mt-5" x-show="step<4">

            <button x-show="step>1"
                    @click="prevStep()"
                    class="btn btn-light">
                Back
            </button>

            <button @click="nextStep()"
                    class="next-button ms-auto">
                <span x-text="step===3 ? 'Submit' : 'Next'"></span>
            </button>

        </div>

    </div>
</div>

<section class="sell-process-section py-5">

    <div class="container-xl">

        {{-- TOP 3 STEPS --}}
        <div class="row g-4 mb-5">

            {{-- STEP 1 --}}
            <div class="col-lg-4">
                <div class="process-card">
                    <img src="{{ asset('theme/img/sell-car-01.jpg') }}" class="img-fluid process-img">
                    <div class="process-content">
                        <span class="step-number">01</span>
                        <h5>Share Your Car Details</h5>
                        <p>
                            Provide a few details about your car, and
                            we'll give you the best price instantly.
                        </p>
                    </div>
                </div>
            </div>

            {{-- STEP 2 --}}
            <div class="col-lg-4">
                <div class="process-card">
                    <img src="{{ asset('theme/img/sell-car-02.jpg') }}" class="img-fluid process-img">
                    <div class="process-content">
                        <span class="step-number">02</span>
                        <h5>Car Inspection</h5>
                        <p>
                            Our experts will inspect your car in person
                            and present a final offer.
                        </p>
                    </div>
                </div>
            </div>

            {{-- STEP 3 --}}
            <div class="col-lg-4">
                <div class="process-card">
                    <img src="{{ asset('theme/img/sell-car-03.jpg') }}" class="img-fluid process-img">
                    <div class="process-content">
                        <span class="step-number">03</span>
                        <h5>Secure Payment & Pick-Up</h5>
                        <p>
                            We complete the payment and arrange
                            your car's pick-up quickly.
                        </p>
                    </div>
                </div>
            </div>

        </div>

        {{-- BOTTOM SECTION --}}
        <div class="row align-items-center g-5">

            {{-- LEFT --}}
            <div class="col-lg-6">
                <h2 class="buy-heading">
                    We buy your car in a <span>Snap</span>
                </h2>
                <p class="buy-sub">
                    Contact us today to get an immediate appraisal and offer on your car.
                </p>

                <a href="{{ route('sell.car') }}" class="sell-btn">
                    Sell Your Car Now
                </a>
            </div>

            {{-- RIGHT --}}
            <div class="col-lg-6">

                <div class="option-card">
                    <h6>💰 Cash out today</h6>
                    <p>
                        Inspect your car today and get an immediate quotation.
                        We help with bank settlement if necessary.
                    </p>
                </div>

                <div class="option-card">
                    <h6>🔁 Trade-in</h6>
                    <p>
                        Upgrade your car today. We'll exchange your old car
                        for a new one.
                    </p>
                </div>

                <div class="option-card">
                    <h6>🚗 Consignment</h6>
                    <p>
                        You deliver your car to our showroom and receive
                        the payment when we sell it.
                    </p>
                </div>

            </div>

        </div>

    </div>

</section>

<section class="sell-content-section py-5">

    <div class="container-xl">

        <div class="content-box">

            <h2 class="content-heading">
                Sell Your Car with a Clear & Transparent Process
            </h2>

            <p class="content-text">
                If you're looking to sell your car quickly and confidently, our streamlined process makes it simple.
                From submitting your vehicle details to inspection and receiving a final offer, everything is designed
                to be smooth and transparent.
            </p>

            <p class="content-text">
                Start online by sharing your car information, or visit our showroom for an in-person evaluation.
                Our team carefully inspects the vehicle and provides a fair market-based price based on condition,
                mileage, and specifications.
            </p>

            <p class="content-text">
                We offer flexible options including instant cash payment, trade-in upgrades, and consignment sales.
                If your vehicle is under finance, we also assist with bank settlement to ensure a hassle-free transfer.
                Wherever you're located, our team will guide you through every step.
            </p>

        </div>

    </div>

</section>

<section class="faq-section py-5">

    <div class="container-xl">

        <h2 class="faq-heading mb-4">Frequently Asked Questions</h2>

        <div class="accordion" id="sellFaq">

            {{-- FAQ 1 --}}
            <div class="faq-card">
                <button class="faq-question" data-bs-toggle="collapse" data-bs-target="#faq1">
                    Can I sell my car completely online?
                    <span class="faq-icon">+</span>
                </button>

                <div id="faq1" class="collapse" data-bs-parent="#sellFaq">
                    <div class="faq-answer">
                        Yes. You can submit your car details online, receive an estimated offer,
                        and schedule an inspection at your convenience.
                    </div>
                </div>
            </div>

            {{-- FAQ 2 --}}
            <div class="faq-card">
                <button class="faq-question" data-bs-toggle="collapse" data-bs-target="#faq2">
                    What is the consignment option?
                    <span class="faq-icon">+</span>
                </button>

                <div id="faq2" class="collapse" data-bs-parent="#sellFaq">
                    <div class="faq-answer">
                        With consignment, you leave your car with us and we handle marketing and selling.
                        Once the vehicle is sold, you receive the agreed payment.
                    </div>
                </div>
            </div>

            {{-- FAQ 3 --}}
            <div class="faq-card">
                <button class="faq-question" data-bs-toggle="collapse" data-bs-target="#faq3">
                    How does the inspection process work?
                    <span class="faq-icon">+</span>
                </button>

                <div id="faq3" class="collapse" data-bs-parent="#sellFaq">
                    <div class="faq-answer">
                        Our experts inspect your car’s mechanical condition, exterior, interior,
                        and documentation before presenting a final offer.
                    </div>
                </div>
            </div>

        </div>

    </div>

</section>

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
function sellForm() {
    return {

        step: 1,
        steps: ['Personal details','Car details','Images'],
        models: [],
        previewImages: [],

        form: {
            name: '',
            phone: '',
            make_id: '',
            model_id: '',
            year: '',
            price: '',
            images: []
        },

        errors: {},

        get progressWidth() {
            return (this.step - 1) * 50;
        },

        validateStep() {

            this.errors = {};

            if(this.step === 1){
                if(!this.form.name) this.errors.name = "Full name required";
                if(!this.form.phone) this.errors.phone = "Phone required";
            }

            if(this.step === 2){
                if(!this.form.make_id) this.errors.make_id = "Select make";
                if(!this.form.model_id) this.errors.model_id = "Select model";
                if(!this.form.year) this.errors.year = "Enter year";
                if(!this.form.price) this.errors.price = "Enter price";
            }

            return Object.keys(this.errors).length === 0;
        },

        fetchModels() {
            fetch(`/sell/models/${this.form.make_id}`)
                .then(res => res.json())
                .then(data => this.models = data);
        },

        handleImages(event) {
            this.form.images = event.target.files;
            this.previewImages = [];

            Array.from(event.target.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = e => this.previewImages.push(e.target.result);
                reader.readAsDataURL(file);
            });
        },

        nextStep() {

            if(!this.validateStep()) return;

            if(this.step < 3){
                this.step++;
            } else {
                this.submitForm();
            }
        },

        prevStep(){
            this.step--;
        },

        submitForm(){

            let formData = new FormData();

            for(let key in this.form){
                if(key !== 'images'){
                    formData.append(key,this.form[key]);
                }
            }

            for(let i=0;i<this.form.images.length;i++){
                formData.append('images[]',this.form.images[i]);
            }

            fetch("{{ route('sell.submit') }}",{
                method:"POST",
                headers:{
                    'X-CSRF-TOKEN':"{{ csrf_token() }}"
                },
                body:formData
            })
            .then(res=>res.json())
            .then(data=>{
                if(data.status){
                    this.step = 4;
                }
            });
        }
    }
}
</script>
@endpush

@endsection