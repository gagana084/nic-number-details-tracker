 function createInfoCard(icon, label, value, colorClass = 'bg-gray-50') {
            return `
                <div class="p-4 ${colorClass} rounded-xl border border-gray-100 hover:shadow-md transition-shadow duration-200 transform hover:scale-[1.02]">
                    <div class="flex items-start space-x-3">
                        <div class="text-xl">${icon}</div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600 mb-1">${label}</p>
                            <p class="text-lg font-semibold text-gray-800">${value}</p>
                        </div>
                    </div>
                </div>
            `;
        }

        function createSectionHeader(icon, title, subtitle = '') {
            return `
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-2">
                        <div class="text-2xl">${icon}</div>
                        <h3 class="text-xl font-semibold text-gray-800">${title}</h3>
                    </div>
                    ${subtitle ? `<span class="text-sm text-gray-500">${subtitle}</span>` : ''}
                </div>
            `;
        }

        function displayResults(data) {
            const resultsDiv = document.getElementById('results');
            
            resultsDiv.innerHTML = '';

            const nicInfoSection = document.createElement('div');
            nicInfoSection.className = 'bg-gradient-to-r from-blue-50 to-cyan-50 rounded-2xl shadow-lg p-6 mb-6 animate-slide-up border border-blue-100';
            nicInfoSection.innerHTML = `
                ${createSectionHeader('🆔', 'NIC Information', data.nic_format)}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    ${createInfoCard('📋', 'NIC Number', data.nic_number, 'bg-white')}
                    ${createInfoCard('📄', 'Format Type', data.nic_format, 'bg-white')}
                    ${createInfoCard('🕒', 'Generated At', new Date(data.generated_at).toLocaleString(), 'bg-white')}
                </div>
            `;

            // Personal Information
            const personalSection = document.createElement('div');
            personalSection.className = 'bg-white rounded-2xl shadow-lg p-6 mb-6 animate-slide-up';
            personalSection.innerHTML = `
                ${createSectionHeader('👤', 'Personal Information')}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    ${createInfoCard(data.personal_information.gender_emoji, 'Gender', data.personal_information.gender, data.personal_information.gender === 'Female' ? 'bg-pink-50' : 'bg-blue-50')}
                    ${createInfoCard('🎂', 'Birth Date', data.personal_information.birth_date_formatted, 'bg-green-50')}
                    ${createInfoCard('📅', 'Age', data.personal_information.age_formatted, 'bg-purple-50')}
                    ${createInfoCard('📊', 'Day of Year', data.personal_information.day_of_year, 'bg-yellow-50')}
                    ${createInfoCard('🌟', 'Life Stage', data.personal_information.life_stage, 'bg-indigo-50')}
                    ${createInfoCard('📈', 'Days Lived', data.personal_information.total_days_lived.toLocaleString(), 'bg-orange-50')}
                </div>
            `;

            // Birth Details
            const birthSection = document.createElement('div');
            birthSection.className = 'bg-white rounded-2xl shadow-lg p-6 mb-6 animate-slide-up';
            birthSection.innerHTML = `
                ${createSectionHeader('🌟', 'Birth Details')}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    ${createInfoCard('📅', 'Birth Day', data.birth_details.birth_day, 'bg-indigo-50')}
                    ${createInfoCard('♈', 'Zodiac Sign', data.birth_details.zodiac_sign, 'bg-purple-50')}
                    ${createInfoCard('🐉', 'Chinese Zodiac', data.additional_insights.chinese_zodiac, 'bg-red-50')}
                    ${createInfoCard('💎', 'Birthstone', data.additional_insights.birthstone, 'bg-pink-50')}
                    ${createInfoCard('🌸', 'Season Born', data.additional_insights.season_born, 'bg-green-50')}
                    ${createInfoCard('🎉', 'Next Birthday', `${data.birth_details.days_until_birthday} days away`, 'bg-yellow-50')}
                </div>
            `;

            // Civic Information
            const civicSection = document.createElement('div');
            civicSection.className = 'bg-white rounded-2xl shadow-lg p-6 mb-6 animate-slide-up';
            civicSection.innerHTML = `
                ${createSectionHeader('🏛️', 'Civic Information')}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    ${createInfoCard('🗳️', 'Voting Status', data.civic_information.voting_eligibility, data.civic_information.voting_eligible ? 'bg-green-50' : 'bg-red-50')}
                    ${createInfoCard('🌟', 'Generation', data.civic_information.generation, 'bg-orange-50')}
                    ${createInfoCard('🏢', 'Gov. Retirement', data.civic_information.government_retirement_year, 'bg-blue-50')}
                    ${createInfoCard('🏭', 'Private Retirement', data.civic_information.private_retirement_year, 'bg-cyan-50')}
                </div>
            `;

            // Technical Information
            const technicalSection = document.createElement('div');
            technicalSection.className = 'bg-white rounded-2xl shadow-lg p-6 mb-6 animate-slide-up';
            technicalSection.innerHTML = `
                ${createSectionHeader('⚙️', 'Technical Information')}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    ${createInfoCard('🔢', 'Serial Number', data.technical_information.serial_number, 'bg-gray-50')}
                    ${createInfoCard('✅', 'Check Digit', data.technical_information.check_digit, 'bg-gray-50')}
                    ${createInfoCard('📝', 'Format Suffix', data.technical_information.format_suffix, 'bg-gray-50')}
                    ${createInfoCard('📍', 'District Code', data.technical_information.district_code, 'bg-blue-50')}
                    ${createInfoCard('🏘️', 'District', data.technical_information.district_name, 'bg-cyan-50')}
                    ${createInfoCard('📊', 'Original Day No.', data.technical_information.original_day_number, 'bg-yellow-50')}
                </div>
            `;

            const insightsSection = document.createElement('div');
            insightsSection.className = 'bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl shadow-lg p-6 mb-6 animate-slide-up border border-purple-100';
            insightsSection.innerHTML = `
                ${createSectionHeader('💡', 'Additional Insights')}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    ${createInfoCard('🏛️', 'Century', data.additional_insights.birth_century, 'bg-white')}
                    ${createInfoCard('👶', 'Age Category', data.additional_insights.age_category, 'bg-white')}
                </div>
            `;

            resultsDiv.appendChild(nicInfoSection);
            resultsDiv.appendChild(personalSection);
            resultsDiv.appendChild(birthSection);
            resultsDiv.appendChild(civicSection);
            resultsDiv.appendChild(technicalSection);
            resultsDiv.appendChild(insightsSection);

            resultsDiv.classList.remove('hidden');

            const sections = resultsDiv.querySelectorAll('.animate-slide-up');
            sections.forEach((section, index) => {
                section.style.animationDelay = `${index * 0.1}s`;
            });
        }

        function showError(response) {
            const errorDiv = document.getElementById('error');
            const errorMessage = document.getElementById('error-message');
            
            let message = 'An error occurred while processing your request.';
            
            if (response.error) {
                message = response.error;
                if (response.error_code) {
                    message += ` (Code: ${response.error_code})`;
                }
            }
            
            errorMessage.textContent = message;
            errorDiv.classList.remove('hidden');
        }

        function hideAll() {
            document.getElementById('results').classList.add('hidden');
            document.getElementById('error').classList.add('hidden');
            document.getElementById('loading').classList.add('hidden');
        }

 
        document.getElementById('btn').addEventListener('click', async () => {
            const nic = document.getElementById('nic').value.trim().toUpperCase();

            if (!nic) {
                showError({error: 'Please enter a NIC number.'});
                return;
            }

            hideAll();
            document.getElementById('loading').classList.remove('hidden');

            try {
                const response = await fetch(`nicDetailsLoadProcess.php?nic=${encodeURIComponent(nic)}`);
                const data = await response.json();

                document.getElementById('loading').classList.add('hidden');

                if (!data.success || data.error) {
                    showError(data);
                } else {
                    displayResults(data);
                }
            } catch (error) {
                document.getElementById('loading').classList.add('hidden');
                showError({error: 'Network error: ' + error.message});
            }
        });

        document.getElementById('nic').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('btn').click();
            }
        });

        document.getElementById('nic').addEventListener('input', function(e) {
            let value = e.target.value.toUpperCase().replace(/[^0-9VX]/g, '');
            
            if (value.length > 10 && value.includes('V') || value.includes('X')) {
                value = value.substring(0, 10);
            } else if (value.length > 12) {
                value = value.substring(0, 12);
            }
            
            e.target.value = value;
            
            const input = e.target;
            if (value.length === 0) {
                input.placeholder = 'e.g., 200145601234 or 991234567V';
            } else if (value.length <= 10) {
                input.placeholder = 'Old format: 10 digits + V/X';
            } else {
                input.placeholder = 'New format: 12 digits';
            }
        });

        document.getElementById('nic').addEventListener('blur', function(e) {
            const value = e.target.value;
            const input = e.target;
            
            if (value.length > 0) {
                const isValidOld = /^\d{9}[VX]$/.test(value) && value.length === 10;
                const isValidNew = /^\d{12}$/.test(value) && value.length === 12;
                
                if (isValidOld || isValidNew) {
                    input.classList.remove('border-red-300');
                    input.classList.add('border-green-300');
                } else {
                    input.classList.remove('border-green-300');
                    input.classList.add('border-red-300');
                }
            } else {
                input.classList.remove('border-red-300', 'border-green-300');
            }
        });

        document.getElementById('nic').addEventListener('focus', function(e) {
            e.target.classList.remove('border-red-300', 'border-green-300');
        });