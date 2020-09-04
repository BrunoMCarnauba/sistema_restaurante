import * as React from 'react';
import { View, Text, StyleSheet, Modal, ActivityIndicator } from 'react-native';

/** Propriedades do loading */
export interface AppProps {
    statusLoading: boolean
}

/**
 * Design do loading
 * @param props 
 */
export function LoadingAnimation (props: AppProps) {
    return (
        <Modal transparent={true} animationType={'none'} visible={props.statusLoading}>
            <View style={styles.modalBackground}>
                <View style={styles.activityIndicatorContainer}>
                    <ActivityIndicator animating={props.statusLoading} size={'large'} color={'white'}/>
                    <Text style={{color: 'white'}}>Carregando...</Text>
                </View>
            </View>
        </Modal>
    );
}

//Css a  ser aplicado 
const styles = StyleSheet.create({
  modalBackground: {
    flex: 1,
    alignItems: 'center',
    flexDirection: 'column',
    justifyContent: 'space-around',
    backgroundColor: '#00000040'
  },
  activityIndicatorContainer: {
    backgroundColor: '#981e13',
    height: 100,
    width: 100,
    borderRadius: 10,
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'space-around'
  }
});