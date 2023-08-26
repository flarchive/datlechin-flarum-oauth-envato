import app from 'flarum/admin/app';
import { ConfigureWithOAuthPage } from '@fof-oauth';

app.initializers.add('datlechin/flarum-oauth-envato', () => {
  app.extensionData.for('datlechin-oauth-envato').registerPage(ConfigureWithOAuthPage);
});
