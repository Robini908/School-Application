<div x-data="{ activeTab: 'promotions' }">
    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a @click="activeTab = 'promotions'; $wire.setTransitionType('promotion')" 
               class="nav-link" :class="{ 'active': activeTab === 'promotions' }" href="#">
                Promotions
            </a>
        </li>
        <li class="nav-item">
            <a @click="activeTab = 'demotions'; $wire.setTransitionType('demotion')" 
               class="nav-link" :class="{ 'active': activeTab === 'demotions' }" href="#">
                Demotions
            </a>
        </li>
        <li class="nav-item">
            <a @click="activeTab = 'repetitions'; $wire.setTransitionType('repetition')" 
               class="nav-link" :class="{ 'active': activeTab === 'repetitions' }" href="#">
                Repetitions
            </a>
        </li>
        <li class="nav-item">
            <a @click="activeTab = 'graduations'; $wire.setTransitionType('graduation')" 
               class="nav-link" :class="{ 'active': activeTab === 'graduations' }" href="#">
                Graduations
            </a>
        </li>
    </ul>

    <!-- Tabs Content -->
    <div x-show="activeTab === 'promotions'">
        <div class="container-fluid">
            <h2 class="card-title mb-4">Promote Student</h2>
            <form wire:submit.prevent="saveTransition">
                @include('partials.transition-form', ['transitionType' => 'promotion'])
            </form>
        </div>
    </div>

    <div x-show="activeTab === 'demotions'">
        <div class="container-fluid">
            <h2 class="card-title mb-4">Demote Student</h2>
            <form wire:submit.prevent="saveTransition">
                @include('partials.transition-form', ['transitionType' => 'demotion'])
            </form>
        </div>
    </div>

    <div x-show="activeTab === 'repetitions'">
        <div class="container-fluid">
            <h2 class="card-title mb-4">Repeat Student</h2>
            <form wire:submit.prevent="saveTransition">
                @include('partials.transition-form', ['transitionType' => 'repetition'])
            </form>
        </div>
    </div>

    <div x-show="activeTab === 'graduations'">
        <div class="container-fluid">
            <h2 class="card-title mb-4">Graduate Student</h2>
            <form wire:submit.prevent="saveTransition">
                @include('partials.transition-form', ['transitionType' => 'graduation'])
            </form>
        </div>
    </div>

    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="alert alert-success mt-4">
            {{ session('message') }}
        </div>
    @endif
</div>

