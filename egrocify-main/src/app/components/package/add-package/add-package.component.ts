import { Component } from '@angular/core';
import { EditorModule } from 'primeng/editor';

@Component({
  selector: 'app-add-package',
  standalone: true,
  imports: [EditorModule],
  templateUrl: './add-package.component.html',
  styleUrl: './add-package.component.scss'
})
export class AddPackageComponent {

}
