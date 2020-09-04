import { registerRootComponent } from 'expo';
import { activateKeepAwake } from 'expo-keep-awake';

import LoginScreen from './src/screen/login';
import navigation from './src/navigations';
import AppTSX from './App';

if (__DEV__) {
  activateKeepAwake();
}

registerRootComponent(navigation);
