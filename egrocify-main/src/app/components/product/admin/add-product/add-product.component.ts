import { Component } from '@angular/core';
import { EditorModule } from 'primeng/editor';

@Component({
  selector: 'app-add-product',
  standalone: true,
  imports: [EditorModule],
  templateUrl: './add-product.component.html',
  styleUrl: './add-product.component.scss'
})
export class AddProductComponent {

}
