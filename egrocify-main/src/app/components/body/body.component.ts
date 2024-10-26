import { Component, OnInit } from '@angular/core';
import { MenuComponent } from '../menu/menu.component';
import { Menu } from '../../interface/menu';
import { Const } from '../../general/const';
import { RouterLink, RouterModule, RouterOutlet } from '@angular/router';
import { CommonModule } from '@angular/common';
import { HeaderComponent } from '../header/header.component';

@Component({
  selector: 'app-body',
  standalone: true,
  imports: [CommonModule, RouterOutlet, RouterModule, RouterLink, MenuComponent, HeaderComponent],
  templateUrl: './body.component.html',
  styleUrl: './body.component.scss'
})
export class BodyComponent implements OnInit {


  constructor() {
  }
  ngOnInit(): void {
    //Called after the constructor, initializing input properties, and the first call to ngOnChanges.
    //Add 'implements OnInit' to the class.

  }
}
