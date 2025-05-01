import KalmanFilter from 'kalmanjs';
import { Sfu, RoomEvent, SfuEvent, State, StatsType } from "@flashphoner/sfusdk";
export declare function createRoom(options: {
    url: string;
    roomName: string;
    pin: string;
    nickname: string;
    failedProbesThreshold?: number;
    pingInterval?: number;
}): Promise<Sfu>;
export declare const constants: {
    SFU_EVENT: typeof SfuEvent;
    SFU_ROOM_EVENT: typeof RoomEvent;
    SFU_STATE: typeof State;
    SFU_RTC_STATS_TYPE: typeof StatsType;
};
export declare function createFilter(): KalmanFilter;
