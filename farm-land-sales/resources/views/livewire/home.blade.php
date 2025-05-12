<div>
    <div class="hero-section">
        <div class="hero-content">
            <h1>Your One-Stop Platform for Agricultural Lands and Farm Equipment</h1>
            <p>Find the perfect agricultural land or quality farm equipment to grow your business</p>
            <div class="hero-buttons">
                <a href="{{ route('lands.search') }}" class="button button-large">Search Agricultural Lands</a>
                <a href="{{ route('equipment.search') }}" class="button button-secondary button-large">Browse Farm Equipment</a>
            </div>
        </div>
    </div>
    
    <div class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-box">
                    <div class="stat-number">{{ $totalLands }}</div>
                    <div class="stat-title">Agricultural Lands</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">{{ $totalEquipment }}</div>
                    <div class="stat-title">Equipment Items</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">{{ $totalAnnouncements }}</div>
                    <div class="stat-title">Total Listings</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="featured-section">
        <div class="container">
            <h2 class="section-title">Featured Agricultural Lands</h2>
            
            <div class="grid">
                @foreach($featuredLands as $land)
                <div class="item-card">
                    <div class="item-image" style="background-image: url('{{ asset('storage/' . ($land->images ?? 'placeholder-land.jpg')) }}')"></div>
                    <div class="item-details">
                        <h3 class="item-title">{{ $land->description }}</h3>
                        <div class="item-price">{{ $land->prix }} €</div>
                        <div class="item-location">{{ $land->adresse }}</div>
                        <div class="item-specs">
                            <span>{{ $land->superficie }} sqm</span>
                        </div>
                        <a href="{{ route('lands.detail', $land->id) }}" class="button">View Details</a>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="view-all-link">
                <a href="{{ route('lands.search') }}">View All Agricultural Lands →</a>
            </div>
        </div>
    </div>
    
    <div class="featured-section featured-equipment-section">
        <div class="container">
            <h2 class="section-title">Featured Farm Equipment</h2>
            
            <div class="grid">
                @foreach($featuredEquipment as $equipment)
                <div class="item-card">
                    <div class="item-image" style="background-image: url('{{ asset('storage/' . ($equipment->documentCatalogue ?? 'placeholder-equipment.jpg')) }}')"></div>
                    <div class="item-details">
                        <h3 class="item-title">{{ $equipment->typeEquipment }}</h3>
                        <div class="item-price">{{ $equipment->prix }} €</div>
                        <div class="item-condition">Condition: {{ $equipment->estNeuf ? 'New' : 'Used' }}</div>
                        <a href="/equipment/{{ $equipment->id }}" class="button">View Details</a>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="view-all-link">
                <a href="{{ route('equipment.search') }}">View All Farm Equipment →</a>
            </div>
        </div>
    </div>
    
    <div class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Ready to List Your Agricultural Land or Farm Equipment?</h2>
                <p>Join our platform today and reach thousands of potential buyers.</p>
                <a href="{{ route('register') }}" class="button button-large">Register Now</a>
            </div>
        </div>
    </div>
    
    <div class="how-it-works-section">
        <div class="container">
            <h2 class="section-title">How It Works</h2>
            
            <div class="steps-grid">
                <div class="step-item">
                    <div class="step-number">1</div>
                    <div class="step-title">Create an Account</div>
                    <p>Register as a client to buy or a supplier to sell.</p>
                </div>
                <div class="step-item">
                    <div class="step-number">2</div>
                    <div class="step-title">List or Search</div>
                    <p>List your lands/equipment or search for what you need.</p>
                </div>
                <div class="step-item">
                    <div class="step-number">3</div>
                    <div class="step-title">Connect</div>
                    <p>Message sellers or buyers directly through our platform.</p>
                </div>
                <div class="step-item">
                    <div class="step-number">4</div>
                    <div class="step-title">Complete Transactions</div>
                    <p>Finalize your purchase or sale securely.</p>
                </div>
            </div>
        </div>
    </div>
</div>