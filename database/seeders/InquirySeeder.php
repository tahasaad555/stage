<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Inquiry;
use App\Models\Annonce;
use App\Models\User;

class InquirySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some existing annonces and users
        $annonces = Annonce::with('fournisseur')->take(5)->get();
        $clients = User::where('role', 'client')->take(3)->get();
        
        if ($annonces->isEmpty()) {
            $this->command->warn('No annonces found. Please create some properties first.');
            return;
        }

        $inquiryTypes = ['general', 'purchase', 'lease', 'partnership', 'information'];
        $priorities = ['low', 'medium', 'high', 'urgent'];
        $statuses = ['new', 'read', 'responded', 'closed'];
        $budgetRanges = ['€10,000 - €50,000', '€50,000 - €100,000', '€100,000 - €250,000', '€250,000 - €500,000', '€500,000+'];
        $contactMethods = ['email', 'phone', 'both'];

        $sampleInquiries = [
            [
                'subject' => 'Interested in purchasing vineyard land in Bordeaux',
                'message' => 'Hello, I am very interested in your vineyard property in Bordeaux. Could you please provide more details about the soil quality, current grape varieties, and any existing equipment that comes with the property? I am looking to start a small wine production business and this seems like a perfect fit. When would be a good time to schedule a visit?',
                'client_name' => 'Jean-Pierre Dubois',
                'client_email' => 'jp.dubois@email.com',
                'client_phone' => '+33 6 12 34 56 78',
                'inquiry_type' => 'purchase',
                'budget_range' => '€250,000 - €500,000',
                'priority' => 'high',
                'status' => 'new'
            ],
            [
                'subject' => 'Lease inquiry for organic farming',
                'message' => 'Good day! I am interested in leasing agricultural land for organic vegetable farming. Do you have any properties suitable for organic certification? I would need at least 5 hectares with good water access. What are your lease terms and conditions?',
                'client_name' => 'Marie Blanchard',
                'client_email' => 'marie.blanchard@gmail.com',
                'client_phone' => '+33 6 87 65 43 21',
                'inquiry_type' => 'lease',
                'budget_range' => '€10,000 - €50,000',
                'priority' => 'medium',
                'status' => 'read'
            ],
            [
                'subject' => 'Partnership opportunity for sustainable farming',
                'message' => 'Hello, I represent GreenFarm Innovations and we are looking for landowners interested in sustainable farming partnerships. We provide modern equipment, expertise, and share profits. Would you be interested in discussing this opportunity for your property?',
                'client_name' => 'Ahmed Benali',
                'client_email' => 'a.benali@greenfarm.ma',
                'client_phone' => '+212 6 98 76 54 32',
                'inquiry_type' => 'partnership',
                'budget_range' => '€100,000 - €250,000',
                'priority' => 'urgent',
                'status' => 'responded'
            ],
            [
                'subject' => 'Information about olive grove cultivation',
                'message' => 'Hi there, I am considering investing in olive grove properties. Could you provide information about the olive trees on your property? What is the annual yield, age of the trees, and maintenance requirements?',
                'client_name' => 'Isabella Rodriguez',
                'client_email' => 'isabella.r@oliveoil.es',
                'client_phone' => null,
                'inquiry_type' => 'information',
                'budget_range' => '€50,000 - €100,000',
                'priority' => 'low',
                'status' => 'closed'
            ],
            [
                'subject' => 'Urgent: Looking for large agricultural land',
                'message' => 'We are an agricultural investment company looking for large plots of land (50+ hectares) for crop cultivation. Do you have any properties that meet these requirements? We are ready to make quick decisions and have financing in place.',
                'client_name' => 'Thomas Mueller',
                'client_email' => 't.mueller@agriinvest.de',
                'client_phone' => '+49 172 123 4567',
                'inquiry_type' => 'purchase',
                'budget_range' => '€500,000+',
                'priority' => 'urgent',
                'status' => 'new'
            ],
            [
                'subject' => 'Question about water rights and irrigation',
                'message' => 'Good morning, I am interested in your property but have questions about water rights and irrigation systems. Is there a well on the property? What are the water usage restrictions in the area?',
                'client_name' => 'Sarah Johnson',
                'client_email' => 'sarah.j@farmconsult.uk',
                'client_phone' => '+44 7700 900123',
                'inquiry_type' => 'information',
                'budget_range' => '€100,000 - €250,000',
                'priority' => 'medium',
                'status' => 'read'
            ],
            [
                'subject' => 'Family farm expansion opportunity',
                'message' => 'Hello! Our family has been farming for three generations and we are looking to expand. Your property is adjacent to our current farm. Would you be open to discussing a sale or long-term lease arrangement?',
                'client_name' => 'Pierre Moreau',
                'client_email' => 'p.moreau@fermemoreau.fr',
                'client_phone' => '+33 6 45 23 67 89',
                'inquiry_type' => 'purchase',
                'budget_range' => '€250,000 - €500,000',
                'priority' => 'high',
                'status' => 'new'
            ],
            [
                'subject' => 'Agritourism development inquiry',
                'message' => 'Hi, I am exploring opportunities to develop agritourism projects. Does your property have potential for visitor activities like farm tours, wine tastings, or accommodation? What are the zoning regulations?',
                'client_name' => 'Elena Rossi',
                'client_email' => 'elena.rossi@agritour.it',
                'client_phone' => '+39 340 123 4567',
                'inquiry_type' => 'general',
                'budget_range' => '€50,000 - €100,000',
                'priority' => 'low',
                'status' => 'responded'
            ]
        ];

        foreach ($annonces as $index => $annonce) {
            // Create 1-3 inquiries per property
            $inquiryCount = rand(1, 3);
            
            for ($i = 0; $i < $inquiryCount; $i++) {
                $inquiry = $sampleInquiries[($index * $inquiryCount + $i) % count($sampleInquiries)];
                
                // Randomly assign a client (or keep it as guest inquiry)
                $clientUserId = null;
                if (rand(0, 1) && !$clients->isEmpty()) {
                    $clientUserId = $clients->random()->id;
                }

                Inquiry::create([
                    'annonce_id' => $annonce->id,
                    'client_user_id' => $clientUserId,
                    'supplier_user_id' => $annonce->fournisseur->user_id,
                    'subject' => $inquiry['subject'],
                    'message' => $inquiry['message'],
                    'client_name' => $inquiry['client_name'],
                    'client_email' => $inquiry['client_email'],
                    'client_phone' => $inquiry['client_phone'],
                    'inquiry_type' => $inquiry['inquiry_type'],
                    'budget_range' => $inquiry['budget_range'],
                    'preferred_contact_method' => $contactMethods[array_rand($contactMethods)],
                    'status' => $inquiry['status'],
                    'priority' => $inquiry['priority'],
                    'responded_at' => $inquiry['status'] === 'responded' ? now()->subDays(rand(1, 7)) : null,
                    'response_message' => $inquiry['status'] === 'responded' ? 'Thank you for your inquiry. I would be happy to discuss this opportunity with you. Please call me at your convenience to arrange a meeting.' : null,
                    'notes' => rand(0, 1) ? 'Follow up needed' : null,
                    'created_at' => now()->subDays(rand(1, 30)),
                    'updated_at' => now()->subDays(rand(0, 15)),
                ]);
            }
        }

        $this->command->info('Sample inquiries created successfully!');
    }
}