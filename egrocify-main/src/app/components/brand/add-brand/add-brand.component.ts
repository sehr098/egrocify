import { Component } from '@angular/core';
import { EditorModule } from 'primeng/editor';

@Component({
  selector: 'app-add-brand',
  standalone: true,
  imports: [EditorModule],
  templateUrl: './add-brand.component.html',
  styleUrl: './add-brand.component.scss'
})
export class AddBrandComponent {

}
