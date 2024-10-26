import { Routes } from '@angular/router';
import { BodyComponent } from './components/body/body.component';
import { AppComponent } from './app.component';
import { MainContentComponent } from './components/main-content/main-content.component';

export const routes: Routes = [
  {
    path: '',
    component: BodyComponent,
    children: [
      {
        path: '',
        loadComponent: () =>
          import('./components/main-content/main-content.component').then(
            (mod) => mod.MainContentComponent
          ),
      },
    ],
  },
    {
      path: 'Company',
      loadComponent: () =>
        import('./components/company/company-list/company-list.component').then(
          (mod) => mod.CompanyListComponent
        ),
    },
    {
      path: 'Add-Company',
      loadComponent: () =>
        import('./components/company/add-company/add-company.component').then(
          (mod) => mod.AddCompanyComponent
        ),
    },
    {
      path: 'Brand',
      loadComponent: () =>
        import('./components/brand/brand-list/brand-list.component').then(
          (mod) => mod.BrandListComponent
        ),
    },
    {
      path: 'Add-Brand',
      loadComponent: () =>
        import('./components/brand/add-brand/add-brand.component').then(
          (mod) => mod.AddBrandComponent
        ),
    },
    {
      path: 'Product',
      loadComponent: () =>
        import('./components/product/admin/product-list/product-list.component').then(
          (mod) => mod.ProductListComponent
        ),
    },
    {
      path: 'Add-Product',
      loadComponent: () =>
        import('./components/product/admin/add-product/add-product.component').then(
          (mod) => mod.AddProductComponent
        ),
    },
    {
      path: 'Order-History',
      loadComponent: () =>
        import('./components/order/admin/order-history/order-history.component').then(
          (mod) => mod.OrderHistoryComponent
        ),
    },
    {
      path: 'Withdraw-Request',
      loadComponent: () =>
        import('./components/withdraw/admin/withdraw-request/withdraw-request.component').then(
          (mod) => mod.WithdrawRequestComponent
        ),
    },
    {
      path: 'User',
      loadComponent: () =>
        import('./components/user/user-list/user-list.component').then(
          (mod) => mod.UserListComponent
        ),
    },
    {
      path: 'Add-User',
      loadComponent: () =>
        import('./components/user/add-user/add-user.component').then(
          (mod) => mod.AddUserComponent
        ),
    },
    {
      path: 'Package',
      loadComponent: () =>
        import('./components/package/package-list/package-list.component').then(
          (mod) => mod.PackageListComponent
        ),
    },
    {
      path: 'Add-Package',
      loadComponent: () =>
        import('./components/package/add-package/add-package.component').then(
          (mod) => mod.AddPackageComponent
        ),
    },
    //seller
    {
      path: 'Order',
      loadComponent: () =>
        import('./components/order/seller/seller-order/seller-order.component').then(
          (mod) => mod.SellerOrderComponent
        ),
    },
    {
      path: 'Withdraw',
      loadComponent: () =>
        import('./components/withdraw/seller/withdraw-list/withdraw-list.component').then(
          (mod) => mod.WithdrawListComponent
        ),
    },
    {
      path: 'Add-Withdraw',
      loadComponent: () =>
        import('./components/withdraw/seller/add-withdraw/add-withdraw.component').then(
          (mod) => mod.AddWithdrawComponent
        ),
    },
    {
      path: 'Referrals',
      loadComponent: () =>
        import('./components/referrals/referrals.component').then(
          (mod) => mod.ReferralsComponent
        ),
    },
    {
      path: 'Subscriptions',
      loadComponent: () =>
        import('./components/subscriptions/subscriptions.component').then(
          (mod) => mod.SubscriptionsComponent
        ),
    },
    {
      path: 'Profile',
      loadComponent: () =>
        import('./components/profile/profile.component').then(
          (mod) => mod.ProfileComponent
        ),
    },
    {
      path: 'Update-Password',
      loadComponent: () =>
        import('./components/profile/update-password/update-password.component').then(
          (mod) => mod.UpdatePasswordComponent
        ),
    },
    {
      path: 'Products',
      loadComponent: () =>
        import('./components/product/seller/product-list/product-list.component').then(
          (mod) => mod.ProductListComponent
        ),
    },
    {
      path: 'Product-Detail',
      loadComponent: () =>
        import('./components/product/seller/product-detail/product-detail.component').then(
          (mod) => mod.ProductDetailComponent
        ),
    },

  { path: '**', redirectTo: '' },
];
