import { CommonModule, NgClass } from '@angular/common';
import { Component, Input, OnInit } from '@angular/core';
import { Menu } from '../../interface/menu';
import { Const } from '../../general/const';
import { RouterLink, RouterLinkActive } from '@angular/router';

@Component({
  selector: 'app-menu',
  standalone: true,
  imports: [NgClass,CommonModule,RouterLinkActive,RouterLink],
  templateUrl: './menu.component.html',
  styleUrl: './menu.component.scss'
})
export class MenuComponent implements OnInit {
  @Input() menuItems: Menu[] = [];
  constants = new Const();
  constructor(){}

  checkRole(role: boolean): boolean {
    return role === true; // For example, if role is true for admin and false for seller
  }

  ngOnInit(): void {
    //Called after the constructor, initializing input properties, and the first call to ngOnChanges.
    //Add 'implements OnInit' to the class.

  }
}
