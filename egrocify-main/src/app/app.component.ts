import { MenuComponent } from './components/menu/menu.component';
import { Component } from '@angular/core';
import { RouterLink, RouterModule, RouterOutlet } from '@angular/router';
import { CommonModule } from '@angular/common';
import { MainContentComponent } from './components/main-content/main-content.component';
import { HeaderComponent } from './components/header/header.component';
import { Const } from './general/const';
import { Menu } from './interface/menu';
import { HttpClientModule } from '@angular/common/http';
@Component({
  selector: 'app-root',
  standalone: true,
  imports: [RouterOutlet,MenuComponent,HeaderComponent,MainContentComponent,HttpClientModule],
  templateUrl: './app.component.html',
  styleUrl: './app.component.scss',
})
export class AppComponent {
  title = 's';
  constants = new Const();
  MenuData: Menu[] = [
    {
      name: 'Dashboard',
      icon: 'mdi-home-outline',
      url: '',
      role: this.constants.admin,
    },
    {
      name: 'PRODUCTS MANAGEMENT',
      icon: '',
      url: '',
      role: this.constants.admin,
      seperator: true
    },
    {
      name: 'Companies',
      icon: 'mdi-window-maximize',
      url: 'Company',
      role: this.constants.admin,
      subItems: [
        {
          name: 'Company List',
          url: 'Company',
          role: this.constants.admin,
          icon: 'mdi-home-outline',
        },
        {
          name: 'Add Company',
          url: 'Add-Company',
          role: this.constants.admin,
          icon: 'mdi-home-outline',
        },
      ]
    },
    {
      name: 'Brands',
      icon: 'mdi-window-maximize',
      url: 'Company',
      role: this.constants.admin,
      subItems: [
        {
          name: 'Brand List',
          url: 'Brand',
          role: this.constants.admin,
          icon: 'mdi-home-outline',
        },
        {
          name: 'Add Brand',
          url: 'Add-Brand',
          role: this.constants.admin,
          icon: 'mdi-home-outline',
        },
      ]
    },
    {
      name: 'Products',
      icon: 'mdi-window-maximize',
      url: 'Company',
      role: this.constants.admin,
      subItems: [
        {
          name: 'Product List',
          url: 'Product',
          role: this.constants.admin,
          icon: 'mdi-home-outline',
        },
        {
          name: 'Add Product',
          url: 'Add-Product',
          role: this.constants.admin,
          icon: 'mdi-home-outline',
        },
      ]
    },
    {
      name: 'Order History',
      icon: 'mdi-home-outline',
      url: 'Order-History',
      role: this.constants.admin,
    },
    {
      name: 'CASH MANAGEMENT',
      icon: '',
      url: '',
      role: this.constants.admin,
      seperator: true
    },
    {
      name: 'Withdraw Requests',
      icon: 'mdi-home-outline',
      url: 'Withdraw-Request',
      role: this.constants.admin,
    },
    {
      name: 'USERS MANAGEMENT',
      icon: '',
      url: '',
      role: this.constants.admin,
      seperator: true
    },
    {
      name: 'Users',
      icon: 'mdi-window-maximize',
      url: 'Company',
      role: this.constants.admin,
      subItems: [
        {
          name: 'User List',
          url: 'User',
          role: this.constants.admin,
          icon: 'mdi-home-outline',
        },
        {
          name: 'Add User',
          url: 'Add-User',
          role: this.constants.admin,
          icon: 'mdi-home-outline',
        },
      ]
    },
    {
      name: 'Packages',
      icon: 'mdi-window-maximize',
      url: 'Company',
      role: this.constants.admin,
      subItems: [
        {
          name: 'Package List',
          url: 'Package',
          role: this.constants.admin,
          icon: 'mdi-home-outline',
        },
        {
          name: 'Add Package',
          url: 'Add-Package',
          role: this.constants.admin,
          icon: 'mdi-home-outline',
        },
      ]
    },
    //seller
    {
      name: 'Dashboard',
      icon: 'mdi-home-outline',
      url: '',
      role: this.constants.seller,
    },
    {
      name: 'Products',
      icon: 'mdi-home-outline',
      url: 'Products',
      role: this.constants.seller,
    },
    {
      name: 'Orders',
      icon: 'mdi-home-outline',
      url: 'Order',
      role: this.constants.seller,
    },
    {
      name: 'Withdraws',
      icon: 'mdi-window-maximize',
      url: '',
      role: this.constants.seller,
      subItems: [
        {
          name: 'Withdraw List',
          url: 'Withdraw',
          role: this.constants.seller,
          icon: 'mdi-home-outline',
        },
        {
          name: 'Withdraw Request',
          url: 'Add-Withdraw',
          role: this.constants.seller,
          icon: 'mdi-home-outline',
        },
      ]
    },
    {
      name: 'Referrals',
      icon: 'mdi-home-outline',
      url: 'Referrals',
      role: this.constants.seller,
    },
    {
      name: 'Subscriptions',
      icon: 'mdi-home-outline',
      url: 'Subscriptions',
      role: this.constants.seller,
    },
    {
      name: 'Profile',
      icon: 'mdi-home-outline',
      url: 'Profile',
      role: this.constants.seller,
    },
  ];
  constructor(){}

  ngOnInit(): void {
  }

}
