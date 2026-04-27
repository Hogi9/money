<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Feedback;
use App\Models\Menu;
use App\Models\Transaction;
use App\Models\TransactionName;
use App\Models\Wallet;
use App\Observers\MenuObserver;
use App\Policies\CategoryPolicy;
use App\Policies\FeedbackPolicy;
use App\Policies\MenuPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use App\Policies\TransactionNamePolicy;
use App\Policies\TransactionPolicy;
use App\Policies\WalletPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Permission::class, PermissionPolicy::class);
        Gate::policy(Menu::class, MenuPolicy::class);
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(TransactionName::class, TransactionNamePolicy::class);
        Gate::policy(Wallet::class, WalletPolicy::class);
        Gate::policy(Transaction::class, TransactionPolicy::class);
        Gate::policy(Feedback::class, FeedbackPolicy::class);

        Menu::observe(MenuObserver::class);

        View::composer('template.sidebar', function ($view) {
            $menus = Menu::with('children')
                ->whereNull('parent_id')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();

            $view->with('sidebarMenus', $menus);
        });
    }
}
