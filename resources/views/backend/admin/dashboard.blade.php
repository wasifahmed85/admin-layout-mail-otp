<x-admin::layout>
    <x-slot name="title">Admin Dashboard</x-slot>
    <x-slot name="breadcrumb">Dashboard</x-slot>
    <x-slot name="page_slug">admin-dashboard</x-slot>

    <section>

        {{-- <x-admin.theme-toggle /> --}}

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6"
            x-transition:enter="transition-all duration-500" x-transition:enter-start="opacity-0 translate-y-8"
            x-transition:enter-end="opacity-100 translate-y-0">

            <div class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0s;"
                @click="showDetails('users')">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="users" class="w-6 h-6 text-blue-400"></i>
                    </div>
                    <div class="text-green-400 text-sm font-medium flex items-center gap-1">
                        <i data-lucide="trending-up" class="w-3 h-3"></i>
                        +12%
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 dark:text-text-white mb-1"
                    x-text="stats.users.toLocaleString()">
                    12,384</h3>
                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">Total Users</p>
                <div class="mt-4 h-1 bg-white/10 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-blue-400 to-blue-600 rounded-full progress-bar"
                        style="width: 75%;"></div>
                </div>
            </div>

            <div class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0.2s;"
                @click="showDetails('revenue')">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="trending-up" class="w-6 h-6 text-green-400"></i>
                    </div>
                    <div class="text-green-400 text-sm font-medium flex items-center gap-1">
                        <i data-lucide="trending-up" class="w-3 h-3"></i>
                        +23%
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-800/60 dark:text-text-dark-primary mb-1">$<span
                        x-text="stats.revenue.toLocaleString()">48,392</span></h3>
                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">Total Revenue</p>
                <div class="mt-4 h-1 bg-white/10 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-green-400 to-green-600 rounded-full progress-bar"
                        style="width: 60%;"></div>
                </div>
            </div>

            <div class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0.4s;"
                @click="showDetails('orders')">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="shopping-bag" class="w-6 h-6 text-purple-400"></i>
                    </div>
                    <div class="text-red-400 text-sm font-medium flex items-center gap-1">
                        <i data-lucide="trending-down" class="w-3 h-3"></i>
                        -5%
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-text-white mb-1" x-text="stats.orders.toLocaleString()">
                    2,847</h3>
                <p class="text-text-dark-primary text-sm">Total Orders</p>
                <div class="mt-4 h-1 bg-white/10 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-purple-400 to-purple-600 rounded-full progress-bar"
                        style="width: 45%;"></div>
                </div>
            </div>

            <div class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0.6s;"
                @click="showDetails('active')">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-yellow-500/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="activity" class="w-6 h-6 text-yellow-400"></i>
                    </div>
                    <div class="text-yellow-400 text-sm font-medium flex items-center gap-1">
                        <div class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></div>
                        Live
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-text-white mb-1" x-text="stats.activeUsers.toLocaleString()">847</h3>
                <p class="text-text-dark-primary text-sm">Active Users</p>
                <div class="mt-4 h-1 bg-white/10 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-full pulse-slow progress-bar"
                        style="width: 85%;"></div>
                </div>
            </div>
        </div>

        {{-- <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-transition:enter="transition-all duration-500 delay-200"
            x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">

            <!-- Main Chart -->
            <div class="lg:col-span-2 glass-card rounded-2xl p-6 card-hover">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-text-white mb-1">Revenue Analytics</h3>
                        <p class="text-text-dark-primary text-sm">Monthly revenue breakdown</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <select
                            class="bg-white/10 text-text-white text-sm px-3 py-2 rounded-lg border border-white/20 outline-none">
                            <option value="monthly">Monthly</option>
                            <option value="weekly">Weekly</option>
                            <option value="daily">Daily</option>
                        </select>
                        <button
                            class="btn-primary text-text-white text-sm px-4 py-2 rounded-xl flex items-center gap-2">
                            <i data-lucide="download" class="w-4 h-4"></i>
                            Export
                        </button>
                    </div>
                </div>
                <div class="h-64 relative">
                    <canvas id="revenueChart" class="w-full h-full"></canvas>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="space-y-6">
                <!-- Recent Activity -->
                <div class="glass-card rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-text-white">Recent Activity</h3>
                        <button class="text-text-dark-primary hover:text-text-white transition-colors">
                            <i data-lucide="more-horizontal" class="w-5 h-5"></i>
                        </button>
                    </div>
                    <div class="space-y-4">
                        <template x-for="activity in recentActivity" :key="activity.id">
                            <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/5 transition-colors">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                                    :class="activity.iconBg">
                                    <i :data-lucide="activity.icon" class="w-4 h-4" :class="activity.iconColor"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-text-white text-sm font-medium" x-text="activity.title"></p>
                                    <p class="text-text-dark-primary text-xs" x-text="activity.time"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="glass-card rounded-2xl p-6">
                    <h3 class="text-lg font-bold text-text-white mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <button
                            class="btn-primary p-3 rounded-xl text-text-white text-sm font-medium flex items-center justify-center gap-2 hover:scale-105 transition-transform">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            Add User
                        </button>
                        <button
                            class="bg-white/10 hover:bg-white/20 p-3 rounded-xl text-text-white text-sm font-medium flex items-center justify-center gap-2 border border-white/20 hover:scale-105 transition-all">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                            Send Mail
                        </button>
                        <button
                            class="bg-white/10 hover:bg-white/20 p-3 rounded-xl text-text-white text-sm font-medium flex items-center justify-center gap-2 border border-white/20 hover:scale-105 transition-all">
                            <i data-lucide="file-text" class="w-4 h-4"></i>
                            Reports
                        </button>
                        <button
                            class="bg-white/10 hover:bg-white/20 p-3 rounded-xl text-text-white text-sm font-medium flex items-center justify-center gap-2 border border-white/20 hover:scale-105 transition-all">
                            <i data-lucide="settings" class="w-4 h-4"></i>
                            Settings
                        </button>
                    </div>
                </div>
            </div>
        </div> --}}

        {{-- <!-- Analytics Tab Content -->
        <div x-show="activeTab === 'analytics'" x-transition:enter="transition-all duration-500"
            x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0"
            class="space-y-6">

            <div class="glass-card rounded-2xl p-6">
                <h2 class="text-2xl font-bold text-text-white mb-6">Analytics Dashboard</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white/5 rounded-xl p-4 hover:bg-white/10 transition-colors">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center">
                                <i data-lucide="eye" class="w-5 h-5 text-blue-400"></i>
                            </div>
                            <div>
                                <h4 class="text-text-white font-medium">Page Views</h4>
                                <p class="text-text-dark-primary text-sm">Last 30 days</p>
                            </div>
                        </div>
                        <div class="text-2xl font-bold text-text-white mb-2">1,234,567</div>
                        <div class="flex items-center gap-2 text-sm">
                            <span class="text-green-400">+15.3%</span>
                            <span class="text-text-dark-primary">vs last month</span>
                        </div>
                    </div>

                    <div class="bg-white/5 rounded-xl p-4 hover:bg-white/10 transition-colors">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center">
                                <i data-lucide="mouse-pointer-click" class="w-5 h-5 text-green-400"></i>
                            </div>
                            <div>
                                <h4 class="text-text-white font-medium">Click Rate</h4>
                                <p class="text-text-dark-primary text-sm">Average CTR</p>
                            </div>
                        </div>
                        <div class="text-2xl font-bold text-text-white mb-2">3.42%</div>
                        <div class="flex items-center gap-2 text-sm">
                            <span class="text-green-400">+0.8%</span>
                            <span class="text-text-dark-primary">vs last month</span>
                        </div>
                    </div>

                    <div class="bg-white/5 rounded-xl p-4 hover:bg-white/10 transition-colors">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center">
                                <i data-lucide="clock" class="w-5 h-5 text-purple-400"></i>
                            </div>
                            <div>
                                <h4 class="text-text-white font-medium">Avg. Session</h4>
                                <p class="text-text-dark-primary text-sm">Duration</p>
                            </div>
                        </div>
                        <div class="text-2xl font-bold text-text-white mb-2">4m 32s</div>
                        <div class="flex items-center gap-2 text-sm">
                            <span class="text-red-400">-12s</span>
                            <span class="text-text-dark-primary">vs last month</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Tab Content -->
        <div x-show="activeTab === 'users'" x-transition:enter="transition-all duration-500"
            x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0"
            class="space-y-6">

            <div class="glass-card rounded-2xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-text-white">User Management</h2>
                    <button class="btn-primary px-4 py-2 rounded-xl text-text-white flex items-center gap-2">
                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                        Add User
                    </button>
                </div>

                <!-- User Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-white/10">
                                <th class="text-left text-text-dark-primary font-medium py-3 px-4">User</th>
                                <th class="text-left text-text-dark-primary font-medium py-3 px-4">Email</th>
                                <th class="text-left text-text-dark-primary font-medium py-3 px-4">Role</th>
                                <th class="text-left text-text-dark-primary font-medium py-3 px-4">Status</th>
                                <th class="text-left text-text-dark-primary font-medium py-3 px-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="user in users" :key="user.id">
                                <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-3">
                                            <img :src="user.avatar" :alt="user.name"
                                                class="w-10 h-10 rounded-xl object-cover">
                                            <div>
                                                <div class="text-text-white font-medium" x-text="user.name">
                                                </div>
                                                <div class="text-text-dark-primary text-sm" x-text="user.username">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 text-text-white/80" x-text="user.email"></td>
                                    <td class="py-4 px-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-medium"
                                            :class="user.role === 'admin' ? 'bg-red-500/20 text-red-400' : user
                                                .role === 'manager' ? 'bg-blue-500/20 text-blue-400' :
                                                'bg-gray-500/20 text-gray-400'"
                                            x-text="user.role"></span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-medium"
                                            :class="user.status === 'active' ? 'bg-green-500/20 text-green-400' :
                                                'bg-red-500/20 text-red-400'"
                                            x-text="user.status"></span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-2">
                                            <button class="p-2 rounded-lg hover:bg-white/10 transition-colors">
                                                <i data-lucide="edit" class="w-4 h-4 text-text-dark-primary"></i>
                                            </button>
                                            <button class="p-2 rounded-lg hover:bg-white/10 transition-colors">
                                                <i data-lucide="trash-2" class="w-4 h-4 text-red-400"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Projects Tab Content -->
        <div x-show="activeTab === 'projects'" x-transition:enter="transition-all duration-500"
            x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0"
            class="space-y-6">

            <div class="glass-card rounded-2xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-text-white">Projects</h2>
                    <button class="btn-primary px-4 py-2 rounded-xl text-text-white flex items-center gap-2">
                        <i data-lucide="folder-plus" class="w-4 h-4"></i>
                        New Project
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <template x-for="project in projects" :key="project.id">
                        <div
                            class="bg-white/5 rounded-xl p-6 hover:bg-white/10 transition-all hover:scale-105 cursor-pointer">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                                    :class="project.iconBg">
                                    <i :data-lucide="project.icon" class="w-6 h-6" :class="project.iconColor"></i>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center">
                                        <span class="text-text-white text-xs font-medium"
                                            x-text="project.team"></span>
                                    </div>
                                </div>
                            </div>
                            <h3 class="text-text-white font-bold text-lg mb-2" x-text="project.name"></h3>
                            <p class="text-text-dark-primary text-sm mb-4" x-text="project.description"></p>
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-text-dark-primary text-sm">Progress</span>
                                <span class="text-text-white text-sm font-medium"
                                    x-text="project.progress + '%'"></span>
                            </div>
                            <div class="h-2 bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-blue-400 to-purple-500 rounded-full transition-all duration-1000"
                                    :style="'width: ' + project.progress + '%'"></div>
                            </div>
                            <div class="flex items-center justify-between mt-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium"
                                    :class="project.status === 'active' ? 'bg-green-500/20 text-green-400' :
                                        project.status === 'pending' ?
                                        'bg-yellow-500/20 text-yellow-400' : 'bg-red-500/20 text-red-400'"
                                    x-text="project.status"></span>
                                <span class="text-text-dark-primary text-sm" x-text="project.deadline"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Messages Tab Content -->
        <div x-show="activeTab === 'messages'" x-transition:enter="transition-all duration-500"
            x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0"
            class="space-y-6">

            <div class="glass-card rounded-2xl p-6">
                <h2 class="text-2xl font-bold text-text-white mb-6">Messages</h2>
                <div class="space-y-4">
                    <template x-for="message in messages" :key="message.id">
                        <div
                            class="flex items-start gap-4 p-4 rounded-xl hover:bg-white/5 transition-colors cursor-pointer">
                            <img :src="message.avatar" :alt="message.sender"
                                class="w-12 h-12 rounded-xl object-cover">
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="text-text-white font-medium" x-text="message.sender"></h4>
                                    <span class="text-text-dark-primary text-sm" x-text="message.time"></span>
                                </div>
                                <p class="text-text-white/80 text-sm mb-2" x-text="message.subject"></p>
                                <p class="text-text-dark-primary text-sm" x-text="message.preview"></p>
                            </div>
                            <div x-show="!message.read" class="w-3 h-3 bg-blue-400 rounded-full"></div>
                        </div>
                    </template>
                </div>
            </div>
        </div> --}}
    </section>
</x-admin::layout>
